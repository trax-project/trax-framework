<?php

namespace Trax\DataStore\Http\Responses;

trait MultipartResponse
{

    /**
     * Return a multipart response.
     */
    public function multipartResponse($parts) {
        
        // Generate boundary
        $boundary = md5(rand());
    
        // Content
        $crlf = "\r\n";
        $content = '';
        foreach ($parts as $part) {
             $content .= $this->part($part, $boundary);
        }
        $content .= $crlf.'--'.$boundary.'--'.$crlf;

        // Response
        return response($content, 200)
                    ->header('Content-Type', 'multipart/mixed; boundary="'.$boundary.'"')
                    ->header('Content-Length', mb_strlen($content, '8bit'));
    }
    
    /**
     * Return a part.
     */
     public function part($part, $boundary) {
          $crlf = "\r\n";
          $content = $crlf.'--'.$boundary.$crlf;
  
          // Content type
          $contentType =  isset($part->contentType) ? $part->contentType : 'application/json';
          $content .= 'Content-Type:'.$contentType.$crlf;
          
          // Content length
          $contentLength =  isset($part->length) ? $part->length : mb_strlen($part->content, '8bit');
          $content .= 'Content-Length:'.$contentLength.$crlf;
          
          // Encoding
          $encoding =  isset($part->encoding) ? $part->encoding : 'binary';
          $content .= 'Content-Transfer-Encoding:'.$encoding.$crlf;
          
          // Hash
          $hash =  isset($part->sha2) ? $part->sha2 : hash('sha256', $part->content);
          $content .= 'X-Experience-API-Hash:'.$hash.$crlf;
          
          // Content
          $content .= $crlf.$part->content;
          return $content;
    }
    
    
}

