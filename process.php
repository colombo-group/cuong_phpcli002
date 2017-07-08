<?php
    require 'vendor/autoload.php';
    use Symfony\Component\Process\Process;
    use Symfony\Component\Process\Exception\ProcessFailedException;
    $climate = new League\CLImate\CLImate;
    $climate->arguments->add([
        'fields' => [
            'longPrefix'  => 'fields'
        ]
    ]);
    $climate->arguments->parse();
    $fields = $climate->arguments->get('fields');
    if(isset($fields)) {
        $fileName = $argv[1];
        $param = 'pdfinfo ' . $fileName;
        $allInfo = outProcess($param);
        $display = array();
        foreach (explode(',', $fields) as $item) {
            $display[ucfirst($item)]  = $allInfo[ucfirst($item)];
        }
        print_r($display);
    } else {
        if(isset($argv[1])) {
            $fileName = $argv[1];
            $param = 'pdfinfo ' . $fileName;
            print_r(outProcess($param));
        } else {
            echo 'not found command' . PHP_EOL;
        }
    }
    function outProcess($param) {
        $process = new Process($param);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $arrRoot = array();
        $info = $process->getOutput();
        $arr = explode(PHP_EOL, $info);
        array_pop($arr); //phan tu cuoi rong nen xoa bo
        foreach ($arr as $item) {
            $arrItem = explode(':', $item);
            $arrRoot[$arrItem[0]] = $arrItem[1];
        }
        return $arrRoot;

    }