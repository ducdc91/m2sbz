<?php

namespace Funk\SbzImport\Model;

use Magento\Framework\App\Filesystem\DirectoryList;

class SbzRequest
{

    private $auth_username;
    private $auth_password;
    private $api_base_url = 'http://sbzonline.sbz.ch/api/Execute.asp';
    private $annotation_base_url = 'http://sbzonline.sbz.ch/Api/Annotation.asp';
    private $session;
    private $line_separator;
    private $column_separator;
    private $parameter_separator;
    private $command_separator;
    protected $backendSession;
    protected $scopeConfig;
    protected $logger;
    public $results = array();

    function __construct(
        \Magento\Catalog\Model\Session $backendSession,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Psr\Log\LoggerInterface $logger
    )
    {
        // get config in backend
        $this->scopeConfig = $scopeConfig;
        $this->auth_username = $this->scopeConfig->getValue('settingSbzImport/authentication/username', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->auth_password = $this->scopeConfig->getValue('settingSbzImport/authentication/password', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->logger = $logger;
        $this->backendSession = $backendSession;

        $this->line_separator = chr(1);
        $this->column_separator = chr(2);
        $this->parameter_separator = chr(3);
        $this->command_separator = chr(4);

        $this->createSession(TRUE);
    }

    function createSession($force = FALSE)
    {
        $success = TRUE;

        if ($sessionId = $this->backendSession->getSbzSessionId()) {
            $this->session = $sessionId;
        }

        if (empty($this->session) || $force) {
            $command = array(
                'createSession',
                $this->auth_username,
                $this->auth_password,
            );
            $response = $this->executeRequest($command);
            if ($response->info['http_code'] == 200) {
                $response_data = explode($this->parameter_separator, $response->data);
                if (isset($response_data[2])) {
                    $this->session = $response_data[2];
                    $this->backendSession->setSbzSessionId($this->session);
                } else {
                    $success = FALSE;
                }
            } else {
                $success = FALSE;
            }
        }

        if (!$success) {
            $this->logger->error('Could not create SBZ session.');
        }

        return $success;
    }

    private function executeRequest($command)
    {
        $request = new Request($this->api_base_url, implode($this->parameter_separator, $command));
        $request->execute();
        $response = new \stdClass();
        $response->data = $request->getResponseBody();
        $response->info = $request->getResponseInfo();
        return $response;
    }

    private function processArticleRequest($command)
    {
        $response = $this->executeRequest($command);
        $response_data = explode($this->parameter_separator, $this->convertEncodingISOToUTF($response->data));
        // if code status = 20906 run again
        if (($response_data[0] == '20906') && $this->createSession(TRUE)) {
            $response = $this->executeRequest($command);
            $response_data = explode($this->parameter_separator, $this->convertEncodingISOToUTF($response->data));
        }

        foreach ($response_data as &$data) {
            if (strpos($data, $this->line_separator) !== FALSE) {
                $data = explode($this->line_separator, $data);
                foreach ($data as &$line) {
                    if (strpos($line, $this->column_separator) !== FALSE) {
                        $line = explode($this->column_separator, $line);
                    }
                }
            }
        }

        return $response_data;
    }

    private function preventEndlessLoop($reset = FALSE)
    {
        static $executions_count = 0;
        if ($reset) {
            $executions_count = 0;
            return;
        }

        $executions_count++;
        return $executions_count > 100;
    }

    function getArticle($keywords, $article_db_type = 1, $start = 1, $length = 999)
    {
        if ($this->preventEndlessLoop()) {
            return $this;
        }

        $kurzanzeige = '';
        if (is_numeric($start)) {
            $kurzanzeige .= 'STARTROW' . $this->column_separator . $start . $this->line_separator;
        }
        if (is_numeric($length)) {
            $kurzanzeige .= 'MAXROWS' . $this->column_separator . $length . $this->line_separator;
        }

        if ($article_db_type != 4) {
            $command = array(
                'getArticle',
                $this->session,
                $article_db_type,
                $this->convertEncodingUTFToISO($keywords),
                '',
                $kurzanzeige,
            );
        } else {
            $command = array(
                'getArticle',
                $this->session,
                1406,
                '',
                '',
                $kurzanzeige,
            );
        }

        $response_data = $this->processArticleRequest($command);

        if (isset($response_data[2][1][1])) {
            $total_results_count = $response_data[2][1][1];

            if (isset($response_data[3]) && (count($response_data[3]) > 3)) {
                $intermediary_results = $response_data[3];
                $keys = array_shift($intermediary_results);
                array_pop($intermediary_results);
                array_pop($intermediary_results);
                foreach ($intermediary_results as $result) {
                    $this->results[] = array_combine($keys, $result);
                }

                if (($start + count($intermediary_results) - 1) < $total_results_count) {
                    $this->getArticle($keywords, $start + count($intermediary_results));
                }
            }
        }

        return $this;
    }

    function getArticleDetail($key)
    {
        $command = array(
            'getArticleDetail',
            $this->session,
            $this->convertEncodingUTFToISO($key),
            'MAXROWS' . $this->column_separator . '999' . $this->line_separator,
        );
        //print_r($command);exit;
        $response_data = $this->processArticleRequest($command);
        if (isset($response_data[2]) && (count($response_data[2]) > 3)) {
            $results = $response_data[2];
            $keys = array_shift($results);
            array_pop($results);
            array_pop($results);
            foreach ($results as $result) {
                $this->results[] = array_combine($keys, $result);
            }
        }

        return $this;
    }

    function getSalesinfo($key)
    {
        $command = array(
            'getSalesinfo',
            $this->session,
            $this->convertEncodingUTFToISO($key),
        );

        $response_data = $this->processArticleRequest($command);
        if (isset($response_data[2]) && (count($response_data[2]) > 3)) {
            $results = $response_data[2];
            $keys = array_shift($results);
            array_pop($results);
            array_pop($results);
            $this->results = array_combine($keys, reset($results));
        }

        return $this;
    }

    function clearResults()
    {
        $this->results = array();
        $this->preventEndlessLoop(TRUE);
        return $this;
    }

    function getResults()
    {
        return $this->results;
    }

    function getAnnotation($annotation_id)
    {

        $query = '?sid=' . $this->session . '&aid=' . $annotation_id;
        $download_url = $this->annotation_base_url . $query;
        $cover_file_name = "cover_" . $annotation_id . ".jpg";
        $file_name = $this->downloadImageFromUrl($download_url, $cover_file_name);
        if (empty($file_name) && $this->createSession(TRUE)) {
            $query['sid'] = $this->session;
        }

        $this->results = $file_name;

        return $this;
    }


    function downloadImageFromUrl($url, $file_name = "")
    {
        $result = "";
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $directory = $objectManager->get('Magento\Framework\Filesystem')->getDirectoryWrite(DirectoryList::MEDIA);

        $data = file_get_contents($url);
        $path = $directory->getAbsolutePath('sbz_image') . DIRECTORY_SEPARATOR . $file_name;
        if (!empty($data)) {
            file_put_contents($path, $data);
            $result = $file_name;
        }
        return $result;
    }

    function addOrderNormal()
    {
        $command = array(
            'addOrderNormal',
            $this->session,
        );

        $response_data = $this->processArticleRequest($command);

        if (isset($response_data[1]) && $response_data[1] == 'Ok' && isset($response_data[2])) {
            $this->results = array('order_id' => $response_data[2]);
        }

        return $this;
    }

    function addDetailNormal($sbz_order_id, $article_id, $quantity)
    {
        $command = array(
            'addDetailNormal',
            $this->session,
            $this->convertEncodingUTFToISO($sbz_order_id),
            $this->convertEncodingUTFToISO($article_id),
            1,
            $this->convertEncodingUTFToISO($quantity),
        );

        $response_data = $this->processArticleRequest($command);
        if (isset($response_data[1]) && $response_data[1] == 'Ok' && isset($response_data[2])) {
            $this->results = array('position_number' => $response_data[2]);
        }

        return $this;
    }

    function releaseOrder($sbz_order_id)
    {
        $this->results = array('order_released' => FALSE);

        $command = array(
            'releaseOrder',
            $this->session,
            $this->convertEncodingUTFToISO($sbz_order_id),
        );

        $response_data = $this->processArticleRequest($command);
        if (isset($response_data[1]) && $response_data[1] == 'Ok') {
            $this->results['order_released'] = TRUE;
        }

        return $this;
    }

    function getDownload($sbz_order_ids = array(), $download_status = 'Bereit')
    {
        $command = array(
            'getDownload',
            $this->session,
        );

        $filter = '';
        if ($sbz_order_ids) {
            $filters = array();
            foreach ($sbz_order_ids as $sbz_order_id) {
                $filters[] = 'auftrag=' . $this->convertEncodingUTFToISO($sbz_order_id);
            }
            $filter .= '(' . implode(' or ', $filters) . ')';
        }
        if ($download_status) {
            $filter .= ($filter ? ' and ' : '') . 'downloadstatus=\'' . $this->convertEncodingUTFToISO($download_status) . '\'';
        }
        if ($filter) {
            $command[] = 'FILTER' . $this->column_separator . $filter;
        }

        $response_data = $this->processArticleRequest($command);
        if (isset($response_data[2]) && (count($response_data[2]) > 3)) {
            $results = $response_data[2];
            $keys = array_shift($results);
            array_pop($results);
            array_pop($results);
            foreach ($results as $result) {
                $this->results[] = array_combine($keys, $result);
            }
        }

        return $this;
    }

    function convertEncodingUTFToISO($string)
    {
        return iconv('UTF-8', 'ISO-8859-1', $string);
    }

    function convertEncodingISOToUTF($string)
    {
        return iconv('ISO-8859-1', 'UTF-8', $string);
    }

}

