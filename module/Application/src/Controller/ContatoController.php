<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Controller;

/**
 * Description of ContatoController
 *
 * @author breno
 */
use Zend\View\Model\ViewModel;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part;
use Zend\Mime\Mime;
use Application\Form\ContatoForm;

class ContatoController extends \Zend\Mvc\Controller\AbstractActionController {

    public function indexAction() {

        $form = new ContatoForm();
        $form->get('submit')->setValue('Enviar');
        $dados = ['form' => $form];

        return new viewModel($dados);
    }

    public function enviarAction() {

        $request = $this->getRequest();
        $post = $request->getPost();
        $textoMensagem=$post['menssagem'];
        $remetente=$post['email'];
        $destinatarios='brenohenriqueanacleto@gmail.com';
        $assunto=$post['assunto'];
        $aux=$this->enviar($remetente, $destinatarios, $assunto, $textoMensagem);
        if($aux){
            return $this->redirect()->toRoute('home');
        }
        return $this->redirect()->toRoute('contato');
    }
     public function enviar($remetente, $destinatarios, $assunto, $textoMensagem, $anexos = array(), $replyTo = null,$cco = null) {
        
        $mensagem  = new Message();
        $transport = new Smtp();
        $body      = new MimeMessage();
        $html      = new Part($textoMensagem);
        
        if(empty($remetente)){
            
            $remetente = $this->remetenteDefault;
        }
        
        $html->charset = "UTF-8";
        $html->type    = "text/html";
        $body->addPart($html);
        $mensagem->setEncoding("UTF-8");
        
        $headers = $mensagem->getHeaders();
        
        $mensagem->setHeaders($headers);
        
        if(is_array($remetente)){
            $mensagem->addFrom($remetente['email'],$remetente['nome']);
        }
        else{
            $mensagem->addFrom($remetente);
        }
        
        $mensagem->addTo($destinatarios);
        
        if(!is_null($replyTo)){
            $mensagem->addReplyTo($replyTo);
        }
        if(!is_null($cco)){
            $mensagem->addBcc($cco);
        }
        
        $mensagem->setSubject($assunto);
        
        if(isset($anexos) && (is_array($anexos)) && (sizeof($anexos) > 0)) {
                        
            foreach ($anexos as $anexo) {
                
                $fileContent = fopen($anexo, 'r');
                $attachment = new Part($fileContent);
                $attachment->type = mime_content_type($anexo);
                $attachment->filename = basename($anexo);
                $attachment->disposition = Mime::DISPOSITION_ATTACHMENT;
                // Setting the encoding is recommended for binary data
                $attachment->encoding = Mime::ENCODING_BASE64;
                
                $body->addPart($attachment);
            }
        }
        
        $mensagem->setBody($body);
        $opcoes = new SmtpOptions();
        $transport->setOptions($opcoes);
        
        try{
            
            $transport->send($mensagem);
            
            return true;
        } catch (\Exception $ex) {

            error_log($ex->getMessage());
            return false;
        }
    }

}
