<?php

namespace Trax\DataStore\Http\Requests;

trait MultipartRequest
{

    /**
     * Check if the request is a multipart one.
     */
    public function isMultipart($request) {
        return (traxHasHeader($request, 'Content-Type')
            && strpos(traxHeader($request, 'Content-Type'), 'multipart/mixed') !== false);
    }
    
    /**
     * Return the multipart boundary.
     */
    public function boundary($request) {
        $parts = explode("boundary=", $request->header('Content-Type'));
        if (count($parts) == 2) {
            $boundary = trim($parts[1], ' "');
            if (!empty($boundary)) return $boundary;
        }
        return false;
    }
    
    /**
     * Return the parts of a multipart request.
     */
    public function parts($request) {
        $crlf = "\r\n";
        $boundary = $this->boundary($request);
        $res = array();
        $parts = explode('--'.$boundary.$crlf, $request->getContent());
        array_shift($parts);
        foreach($parts as $part) {
            
            // Parameters
            $params = array();
            $sub = explode($crlf.$crlf, $part);
            if (count($sub) < 2) continue;
            $paramLines = explode($crlf, array_shift($sub));
            foreach($paramLines as $line) {
                $split = explode(':', $line);
                if (count($split) < 2) continue;
                $params[trim($split[0])] = trim($split[1]);
            }
            
            // Content
            $content = implode($crlf.$crlf, $sub);
            $content = str_replace($crlf.'--'.$boundary.'--'.$crlf, '', $content);
            
            // Prepare result
            $partRes = (object)array();
            if (isset($params['Content-Transfer-Encoding'])) $partRes->encoding = $params['Content-Transfer-Encoding'];
            if (isset($params['Content-Length'])) $partRes->length = $params['Content-Length'];
            if (isset($params['Content-Type'])) $partRes->contentType = $params['Content-Type'];
            if (isset($params['X-Experience-API-Hash'])) $partRes->sha2 = $params['X-Experience-API-Hash'];
            $partRes->content = $content;
            if (isset($partRes->sha2)) $res[$partRes->sha2] = $partRes;
            else $res[] = $partRes;
        }
        
        return $res;
    }
    
}

