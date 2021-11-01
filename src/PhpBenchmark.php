<?php
namespace gabrielef;

class PhpBenchmark
{
    protected $iterations;

    protected $tests = [];

    protected $timeBenchmark = [];

    public function __construct($config)
    {
        $this->iterations = $config['iterations'] ?? 1;
    }

    public function addTest($name, $method)
    {
        $this->tests[$name] = $method;
    }

    public function executionTimeBenchmark()
    {
        foreach ($this->tests as $index => $test) {
            //TODO migliorare il calcolo della mediaescludendo il ciclo
            $this->timeBenchmark[$index] = [];
            $this->timeBenchmark[$index]['time'] = new \DateTime('00:00');
            //warmup
            ob_start();
            $test->__invoke();
            for ($i = 0; $i < $this->iterations; $i++) {
                $start = new \DateTime();
                //var_dump($test);
                $test->__invoke();
                $end = new \DateTime();
                $this->timeBenchmark[$index]['time']->add($end->diff($start));
            }
            ob_end_clean();
            $this->averageTime($index, $this->iterations);
        }
    }

    private function averageTime($index, $iterations)
    {
        $diff = (new \DateTime('00:00'))->diff($this->timeBenchmark[$index]['time']);
        $this->timeBenchmark[$index]['iteration'] = $iterations;
        $this->timeBenchmark[$index]['avg'] = ($diff->s + $diff->f) / $iterations;
    }

    public function printStats()
    {
        $baseline = null;
        array_multisort(array_column($this->timeBenchmark, 'avg'), SORT_ASC, SORT_NUMERIC, $this->timeBenchmark);
        foreach ($this->timeBenchmark as $key => $value) {
            if (!isset($baseline)) {
                $baseline = $value['avg'];
            }
            $p = round(100 - ($baseline / $value['avg']) * 100, 3);
            $s = $p >= 0 ? '+' : '-';
            echo "$key: " . $value['avg'] . "   $s $p%\n";
        }
    }
}
