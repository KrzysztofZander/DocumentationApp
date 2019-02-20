<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use Webklex\IMAP\Facades\Client;
use App\Document;
use App\Http\Resources\Document as DocumentResource;

class DocumentController extends Controller
{
    public function checkmail()
    {
          $check=0;
          $oClient = Client::account('default');

    //Connect to the IMAP Server
    $oClient->connect();

    //Get all Mailboxes
    /** @var \Webklex\IMAP\Support\FolderCollection $aFolder */
    $aFolder = $oClient->getFolders();
    $howManyAttach=0;

    //Loop through every Mailbox
    /** @var \Webklex\IMAP\Folder $oFolder */
    foreach($aFolder as $oFolder){

        //Get all Messages of the current Mailbox $oFolder
        /** @var \Webklex\IMAP\Support\MessageCollection $aMessage */
        $aMessage = $oFolder->query()->all()->get();
        
        /** @var \Webklex\IMAP\Message $oMessage */
        foreach($aMessage as $oMessage){

          $check=1;
            echo  $oMessage->getSubject().'<br />';
            echo  'Attachments: '.$oMessage->getAttachments()->count().'<br />';
            $howManyAttach+= $oMessage->getAttachments()->count();
            /** @var \Webklex\IMAP\Message $oMessage */

            /** @var \Webklex\IMAP\Support\AttachmentCollection $aAttachment */
            $aAttachment = $oMessage->getAttachments();

            $aAttachment->each(function ($oAttachment) {

                /** @var \Webklex\IMAP\Attachment $oAttachment */
                $fileName = str_random(32).".pdf";
                $path = 'E:\DocumentationApp\storage\app\Pdf_Files';
                $oAttachment->save($path, $fileName );
                //Storage::put($fileName, $oAttachment);

                $document = new Document;
                $document->file = $fileName;
                $document->status = "wprowadzony";

                //test
                if($i=rand(1,2)==1){
                $document->company = "retencja";
                $document->description = "qwertydsadsadsadsdsadasdsa";
                $document->typeOfDoc = "Umowa";
                $document->InOrOut = "Przychodzący";
                }
                else{
                $document->company = "biopro";
                $document->description = "abcdedsadsadsadsadsadsa";
                $document->typeOfDoc = "Faktura";
                $document->InOrOut = "Wychodzący";
                }
                $document->numberOnDoc = rand(100,1000);

                $date = Carbon::create(2018, 2, 28, 0, 0, 0);
                $document->dateOfDoc = $date->addWeeks(rand(1, 52))->format('Y-m-d H:i:s');
                $document->counterparty = "Kontrahent 1";

                //test

                $document->name = $oAttachment->getName();
                $document->save();
            });

            

        }
    }
    if($check!=0){
        return redirect()->back()->with('msg', 'Pobrano dokumentów : '.$howManyAttach);
    }
    else{
        return redirect()->back()->with('msg_er', 'Brak nowych dokumentów w skrzynce');

    }

  }

  public function getDoc(){

    $documents = Document::orderBy('created_at', 'desc')->get();

    return DocumentResource::collection($documents);

    
  }

  public function downloadDoc($id){

     $document = Document::findOrFail($id);

     return response()->download(storage_path("app\Pdf_Files\\".$document->file));
  }

  public function storeDoc(Request $request){

    $document = new Document;
    
    if(!$request->name){
    $document->name = $request->file->getClientOriginalName();
    }

    else{
      $document->name = $request->name.".pdf";
    }
    $document->company = $request->company;
    $document->inOrOut = $request->inOrOut;
    $document->dateOfDoc = $request->dateOfDoc;
    $document->typeOfDoc = $request->typeOfDoc;
    $document->numberOnDoc = $request->numberOnDoc;
    $document->description = $request->description;
    $document->counterparty = $request->counterparty;
    $document->status = "wprowadzony";
    
    $fileName = str_random(32).".pdf";
    $request->file->storeAs('Pdf_Files',$fileName );
    $document->file = $fileName;

    if($document->save()){

      return redirect()->back()->with('msg', 'Dodano dokument');
    }

  }

   Public function saveDoc (Request $request){

    $document = Document::findOrFail($request->document_id);
    $document->counterparty = $request->input('counterparty');

    if( $request->input('name') != $document->name){
      $document->name = $request->input('name').".pdf";
    }

     if($document->save()) {
       return new DocumentResource($document);
     }

   }

  public function deleteDoc($id){

    $document = Document::findOrFail($id);


    if($document->delete()){

      return new DocumentResource($document);
   }

  }

  public function displayDoc($id){

    $document = Document::findOrFail($id);

    return response()->file(storage_path("app\Pdf_Files\\".$document->file));

  }


}
