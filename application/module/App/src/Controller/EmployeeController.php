<?php
namespace App\Controller;

use App\Service\ImportService;
use Asan\PHPExcel\Excel;
use Asan\PHPExcel\Reader\Csv;
use Framework\Mvc\HttpController;

class EmployeeController extends HttpController
{
    /**
     * Upload Import file
     *
     * @Route(/employee/upload)
     */
    public function uploadAction()
    {
        $response = [
            'status'  => 'error',
            'message' => 'Bad Request'
        ];

        try {
            if (!$this->container->get('request')->isPostRequest()) {
                throw new \Exception('Bad Request');
            }

            if (empty($_FILES)) {
                throw new \Exception('Bad Request');
            }

            $service = new ImportService();
            $result  = $service->read($_FILES['file']);

            $response = [
                'status'            => 'success',
                'members'           => $result['members'],
                'topAverageCouples' => $result['topAverageCouples'],
                'icon'              => '/images/icons/csv-icon.png',
                'fileName'          => $result['topAverageCouples'],
            ];
            $response['members']           = $result['members'];
            $response['average']           = $result['average'];
            $response['topAverageCouples'] = $result['topAverageCouples'];
            $response['icon']              = '/images/xls-icon.png';
            $response['fileName']          = htmlspecialchars($result['fileName'] . ' (' . $result['fileSize'] . ' B)');
        } catch (\Throwable $e) {
            $response['message'] = $e->getMessage();
        }

        $this->renderJson($response);
    }
}