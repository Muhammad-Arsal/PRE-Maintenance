<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\InspectionQuestion;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\InspectionQuestionTitle;

class InspectionQuestionController extends Controller
{
    public function index()
    {
        $page['page_title'] = 'Manage Inspection Questions';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Inspection Questions';

        $inspectionQuestions = InspectionQuestionTitle::orderBy('created_at', 'asc')->paginate(10);
        $keywords = "";

        return view('admin.settings.inspection_questions.index', compact('page', 'inspectionQuestions', 'keywords'));
    }

    public function create()
    {
        $page['page_title'] = 'Manage Inspection Questions';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Add Inspection Questions';

        return view('admin.settings.inspection_questions.create', compact('page'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string',
            'sections'       => 'required|array',
            'sections.*.name'=> 'required|string',
            'sections.*.questions'              => 'required|array',
            'sections.*.questions.*.question_text'=> 'required|string',
        ]);

        $title = InspectionQuestionTitle::create(['title'=>$data['title']]);

        foreach($data['sections'] as $sIdx => $sec){
            $section = $title->inspectionQuestionSections()->create([
                'section_name'  => $sec['name'],
            ]);

            foreach($sec['questions'] as $qIdx => $q){
                $section->inspectionQuestion()->create([
                    'question' => $q['question_text']
                ]);
            }
        }

        return redirect()
                ->route('admin.settings.inspectionQuestions')
                ->withFlashMessage('Inspection Questions Saved Successfully')
                ->withFlashType('success');
    }

    public function edit($id)
    {
        $page['page_title'] = 'Manage Inspection Questions';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Edit Inspection Question';
        
        $inspectionTitle = InspectionQuestionTitle::with(['inspectionQuestionSections.inspectionQuestion'])
            ->findOrFail($id);
        
        return view('admin.settings.inspection_questions.edit', compact('page', 'inspectionTitle'));
    }
    
     public function update(Request $request, $id)
    {
        $inspectionTitle = InspectionQuestionTitle::findOrFail($id);

        $data = $request->validate([
            'title'                         => 'required|string|max:255',
            'sections'                      => 'required|array|min:1',
            'sections.*.name'               => 'required|string|max:255',
            'sections.*.questions'          => 'required|array|min:1',
            'sections.*.questions.*.question' => 'required|string',
        ]);

        DB::transaction(function() use ($inspectionTitle, $data) {
            // Update title
            $inspectionTitle->update(['title' => $data['title']]);

            // Delete old sections/questions and re-create
            $inspectionTitle->inspectionQuestionSections()->each(function($sec) {
                $sec->inspectionQuestion()->delete();
            });
            $inspectionTitle->inspectionQuestionSections()->delete();

            // Re-insert
            foreach ($data['sections'] as $sIdx => $sec) {
                $section = $inspectionTitle->inspectionQuestionSections()->create([
                    'section_name'  => $sec['name'],
                ]);

                foreach ($sec['questions'] as $qIdx => $q) {
                    $section->inspectionQuestion()->create([
                        'question' => $q['question'],
                    ]);
                }
            }
        });

        return redirect()
                ->route('admin.settings.inspectionQuestions')
                ->withFlashMessage('Inspection Questions updated Successfully')
                ->withFlashType('success');
    }

    public function destroy($id)
    {
        $inspectionTitle = InspectionQuestionTitle::with('inspectionQuestionSections.inspectionQuestion')
            ->findOrFail($id);

        DB::transaction(function() use ($inspectionTitle) {

            $inspectionTitle->inspectionQuestionSections->each(function($section) {
                $section->inspectionQuestion()->delete();
            });

            $inspectionTitle->inspectionQuestionSections()->delete();

            $inspectionTitle->delete();
        });

        return redirect()
            ->route('admin.settings.inspectionQuestions')
            ->withFlashMessage('Inspection Questions deleted successfully')
            ->withFlashType('success');
    }

    public function search(Request $request)
    {
        $page['page_title'] = 'Manage Inspection Questions';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Inspection Questions';

        $inspectionQuestions = InspectionQuestionTitle::with('inspectionQuestionSections.inspectionQuestion')
            ->where('title', 'like', '%' . $request->keywords . '%')
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        $keywords = $request->keywords;

        return view('admin.settings.inspection_questions.index', compact('page', 'inspectionQuestions', 'keywords'));
    }



}
