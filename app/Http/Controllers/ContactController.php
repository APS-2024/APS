<?php

namespace App\Http\Controllers;

use App\Mail\AdvertiserFormMail;
use App\Mail\ContactFormMail;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    //

    public function contact(Request $request){


     $validate= Validator::make($request->all(),
     [
        'first_name' => 'required|string|max:255',
        'last_name'  => 'required|string|max:255',
        'email' => 'required|email',
        'web_url' =>'required',
        'skype_contact' =>'required',
        'whatsapp_contact' =>'required',
        'page_view' =>'required',
        'adsense' => 'required'
    
 ] );

 if ($validate->fails()) {
    return response()->json([
        'errors' => $validate->errors()
    ], 422); // Validation error
}

$contact = new Contact();

$contact->first_name= $request->input('first_name');
$contact->last_name= $request->input('last_name');
$contact->email= $request->input('email');
$contact->web_url= $request->input('web_url');
$contact->skype_contact= $request->input('skype_contact');
$contact->whatsapp_contact= $request->input('whatsapp_contact');
$contact->page_view= $request->input('page_view');
$contact->adsense= $request->input('adsense');
$contact->message= $request->input('note');

$contact->save();

$contactData = $request->only('first_name', 'last_name', 'email', 'skype_contact', 'whatsapp_contact', 'message');

    // Send the email
    Mail::to('ranjisaini001@gmail.com')->send(new ContactFormMail($contactData));

return response()->json([
    'success' => true,
    'message' => 'Thank you contact us.'
]);



    }


 
    public function advertiser(){
        
        return view('advertise');
        
    }


    public function advertiserSave(Request $request){

        $validate= Validator::make($request->all(),
        [
           'first_name' => 'required|string|max:255',
           'last_name'  => 'required|string|max:255',
           'email' => 'required|email',
       
    ] );
   
    if ($validate->fails()) {
        return redirect()->back()->withErrors($validate)->withInput();
    }
    
   $contact = new Contact();
   
   $contact->first_name= $request->input('first_name');
   $contact->last_name= $request->input('last_name');
   $contact->email= $request->input('email');
   $contact->message= $request->input('note');
   
   $contact->save();
   
   $contactData = $request->only('first_name', 'last_name', 'email', 'note');

       // Send the email
       Mail::to('test@test.com')->send(new AdvertiserFormMail($contactData));
   
       return redirect(route('advertiser'))->with('success', 'Thanks for contact us');

   
   
       }

}
