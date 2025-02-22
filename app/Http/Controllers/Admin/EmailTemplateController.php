<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use Carbon\Carbon;

class EmailTemplateController extends Controller
{
    public function index(Request $request, EmailTemplate $emailTemplate){
        $page['page_title'] = 'Manage Email Template';

        $searchEmail = $request->input('emailTemplate');
        $keywords = $request->input('keywords');

        $query =  $emailTemplate->withTrashed()->orderBy('type', 'asc');

        if ($keywords) {
            $query->where(function($q) use ($keywords) {
                $q->where('type', 'like', '%' . $keywords . '%')
                  ->orWhere('subject', 'like', '%' . $keywords . '%');
            });
        }

        if ($searchEmail) {
            $query->where('type', $searchEmail);
        }

        $data = $query->paginate(10);

        $searchEmails = EmailTemplate::withTrashed()->orderBy('type', 'asc')->get();

        return view('admin.settings.emailTemplates.index',compact('page', 'data', 'searchEmails', 'searchEmail', 'keywords'));
    }

    public function add(){
        $page['page_title'] = 'Manage Email Template';

        return view('admin.settings.emailTemplates.add',compact('page'));
    }

    public function save(Request $request){
          $request->validate([
              'type' => 'required|unique:email_templates',
              'subject' => 'required|min:6',
              'status' => 'required',
              'is_html' => 'required',
              'description' => 'required',
          ]);

            $data = $request->except('_token');

            $template =  new EmailTemplate;
            $template->type = $data['type'];
            $template->subject = $data['subject'];
            $template->status = $data['status'];
            $template->is_html = $data['is_html'];
            $template->content = base64_encode($data['description']);

            $template->save();

            return redirect()
            ->route( 'admin.emailTemplate.index' )
            ->withFlashMessage( 'Email Template added successfully!' )
            ->withFlashType( 'success' );

    }

    public function edit($id){
        $data = EmailTemplate::withTrashed()->where('id', $id)->first();

        $page['page_title'] = 'Manage Email Template';
        return view('admin.settings.emailTemplates.edit',compact('page','data'));
    }

    public function update($id, Request $request){
        $template = EmailTemplate::withTrashed()->where('id', $id)->first();;
        $request->validate([
            'type' => 'required|unique:email_templates,type,'.$template->id,
            'subject' => 'required|min:6',
            'status' => 'required',
            'is_html' => 'required',
            'description' => 'required',
        ]);

          $data = $request->except('_token');

          $template->type = $data['type'];
          $template->subject = $data['subject'];
          $template->deleted_at = $data['status'] == 1 ? NULL : Carbon::now();
          $template->is_html = $data['is_html'];
          $template->content = base64_encode($data['description']);

          $template->save();

          return redirect()
          ->route( 'admin.emailTemplate.index' )
          ->withFlashMessage( 'Email Template updated successfully!' )
          ->withFlashType( 'success' );

    }

    public function show($id){
        $template =  EmailTemplate::withTrashed()->where('id', $id)->first();
        $page['page_title'] = 'View Email Template';

        return view('admin.settings.emailTemplates.show',compact('page','template'));
    }

     public function searchData(Request $request){
         $keywords =  $request['keywords'];
        $data =  EmailTemplate::where('type', 'like', '%'.$keywords.'%')
                    ->orWhere('subject', 'like', '%'.$keywords.'%')
                    ->withTrashed()
                    ->get();
       return view('admin.ajax.emailTemplateSearchData', compact('data'));
     }

     public function resetData(){
        $data = EmailTemplate::withTrashed()->get();
        return view('admin.ajax.emailTemplateSearchData', compact('data'));
     }

     public function destroy($id){
          $template = EmailTemplate::withTrashed()->find($id);
          if($template->trashed()) {
            $template->restore();

            return redirect()
            ->route( 'admin.emailTemplate.index' )
            ->withFlashMessage( 'Email Template activated successfully!' )
            ->withFlashType( 'success' );
          } else {
            $template->delete();
            return redirect()
            ->route( 'admin.emailTemplate.index' )
            ->withFlashMessage( 'Email Template deactivated successfully!' )
            ->withFlashType( 'success' );
          }
     }
}
