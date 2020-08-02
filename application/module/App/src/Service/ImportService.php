<?php
namespace App\Service;


class ImportService
{
    const FILE_MAX_SIZE = 50*1024*1024;

    /**
     * File fields
     *
     * @var array
     */
    private $fields = [
        'A' => [
            'name'     => 'name',
            'type'     => 'string',
            'required' => true,
        ],
        'B' => [
            'name'     => 'email',
            'type'     => 'string',
            'required' => true
        ],
        'C' => [
            'name'     => 'division',
            'type'     => 'string',
            'required' => true
        ],
        'D' => [
            'name'     => 'age',
            'type'     => 'number',
            'required' => true
        ],
        'E' => [
            'name'     => 'timezone',
            'type'     => 'number',
            'required' => true,
            'min'      => -12,
            'max'      => 12,
        ]
    ];

    /**
     * @param $file
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function read($file)
    {
        if ($file['error']) {
            throw new \Exception('Bad Request');
        }

        if ($file['size'] > self::FILE_MAX_SIZE) {
            throw new \Exception(sprintf('File max size should be less or than %d MB', self::FILE_MAX_SIZE));
        }

        if (!in_array($file['type'], ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])) {
            throw new \Exception('Invalid file extension');
        }

        $tmpPath = APP_BASE_PATH . '..' . DS . 'storage'. DS . 'tmp.xls';

        if (move_uploaded_file($file['tmp_name'], $tmpPath)) {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($tmpPath);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($tmpPath);

            $sheetData  = [];
            $sheetCount = $spreadsheet->getSheetCount();

            for ($i = 0; $i < $sheetCount; $i++) {
                $sheet     = $spreadsheet->getSheet($i);
                $sheetData = $sheet->toArray(null, true, true, true);
            }

            $sheetData = $this->getValidData(array_values($sheetData));
            $averages  = $this->calculateAverages($sheetData);

            $couples = $this->chooseMaxWithRoundRobin(array_keys($sheetData), $averages);

            if (is_null($couples) || !isset($couples['averages'])) {
                throw new \Exception('Invalid parameters for calculation');
            }

            $average = $couples['averages'];
            unset($couples['averages']);
            $average = $average / count($couples);

            $maxAverage = [];

            foreach ($couples as $value) {
                [$first, $second] = explode('-', $value);

                $maxAverage[] = $sheetData[$first]['A'] . ' - ' . $sheetData[$second]['A'];
            }

            return [
                'members'           => $sheetData,
                'topAverageCouples' => $maxAverage,
                'average'           => $average,
                'fileName'          => $file['name'],
                'fileSize'          => $file['size']
            ];
        } else {
            throw new \Exception('Technical Problems: please check storage path permissions');
        }
    }

    /**
     * @param $data
     * @return array
     */
    private function calculateAverages($data)
    {
        $result        = [];

        foreach ($data as $key => &$row) {
            foreach ($data as $compareKey => $compareRow) {

                if ($key == $compareKey) {
                    continue;
                }

                $average = 0;

                if ($row['C'] == $compareRow['C']) {
                    $average += 30;
                }

                if (abs($row['D'] - $compareRow['D']) <= 5) {
                    $average += 30;
                }

                if ($row['E'] == $compareRow['E']) {
                    $average += 40;
                }

                $result[$key . '-' . $compareKey] = $average;
            }
        }

        return $result;
    }

    /**
     * @param $couples
     * @param $averages
     * @return mixed
     */
    private function chooseMaxWithRoundRobin($couples, $averages)
    {
        if (count($couples)%2 != 0){
            array_push($couples,"bye");
        }

        $away = array_splice($couples,(count($couples)/2));
        $home = $couples;

        for ($i=0; $i < count($home) + count($away)-1; $i++){
            for ($j=0; $j < count($home); $j++){
                $round[$i][$j] = $home[$j] . '-' . $away[$j];

                if (!isset($round[$i]['averages'])) {
                    $round[$i]['averages'] = 0;
                }

                $round[$i]['averages'] += $averages[$home[$j] . '-' . $away[$j]];
            }

            if (count($home) + count($away)-1 > 2) {
                $sp = array_splice($home,1,1);
                array_unshift($away, array_shift($sp));
                array_push($home, array_pop($away));
            }
        }

        usort($round, function ($a, $b) {
            return $a['averages'] < $b['averages'];
        });

        return $round[0] ?? null;
    }

    /**
     * @param $data
     * @return array
     * @throws \Exception
     */
    private function getValidData($data)
    {
        if (empty($data)) {
            throw new \Exception('File is empty');
        }

        $result = [];

        foreach ($data as $key => $row) {
            if (!$key) {
                continue;
            }

            foreach ($row as $index => $field) {
                if (!isset($this->fields[$index])) {
                    throw new \Exception('Invalid Columns in file:' . $index);
                }

                if (isset($this->fields[$index]['min']) && $field < $this->fields[$index]['min']) {
                    throw new \Exception('Invalid minimum limitation:' . $field);
                }

                if (isset($this->fields[$index]['max']) && $field > $this->fields[$index]['max']) {
                    throw new \Exception('Invalid maximum limitation:' . $field);
                }
            }

            $result[] = $row;
        }

        return $result;
    }
}