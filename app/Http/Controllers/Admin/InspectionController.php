<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Admin;
use App\Models\Tenant;
use App\Models\Landlord;
use App\Models\Property;
use App\Models\Inspection;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\InspectionQuestionTitle;
use Illuminate\Support\Facades\Notification;
use App\Notifications\InspectionNotification;


class InspectionController extends Controller
{
    public function index()
    {
        $page['page_title'] = 'Manage Inspection';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Inspection';

        $inspections = Inspection::with('assignedTo', 'property', 'template')->orderBy('created_at', 'desc')->paginate(10);

        $keywords = '';
        $admin = Auth::guard('admin')->user();
        $allNotifications = $admin->notifications()->where('data->notification_detail->type', 'Inspection')->whereNull('read_at')->update(['read_at' => Carbon::now()]);

        return view('admin.inspection.index', compact('page', 'inspections', 'keywords'));
    }

    public function create()
    {
        $page['page_title'] = 'Manage Inspection';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Add Inspection';   

        $allAdmins = Admin::all();
        $allProperties = Property::where('status', 'Active')->get();
        $allTemplates = InspectionQuestionTitle::all();

        return view('admin.inspection.create', compact('page', 'allAdmins', 'allProperties', 'allTemplates'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'report_type'   => 'required|in:Inventory,Check In,Check Out,Property',
            'assign_to'     => 'required|exists:admins,id',
            'property_id'   => 'required|exists:properties,id',
            'template_id'   => 'required|exists:inspection_questions_title,id',
            'report_date'   => 'required|date_format:d/m/Y',
            'time_duration' => 'required|string|max:50',
        ]);

        $inspection = Inspection::create([
            'report_type'   => $data['report_type'],
            'assigned_to'   => $data['assign_to'],
            'property_id'   => $data['property_id'],
            'template_id'   => $data['template_id'],
            'date'          => Carbon::createFromFormat('d/m/Y', $data['report_date'])->format('Y-m-d'),
            'time'          => $data['time_duration'],
        ]);

        $property = Property::with('landlord', 'tenant')->findOrFail($data['property_id']);
        $propertyName = $property->line1 . ', ' . $property->city . ', ' . $property->county . ', ' . $property->postcode;
        $inspectionDate = $data['report_date'];
        $inspectionTime = $data['time_duration'];

        $admin = Admin::where('id', $data['assign_to'])->first();
        $landlord = Landlord::where('id', $property->landlord->id)->first();
        $tenant = Tenant::where('id', $property->tenant->id)->first();


        $adminNotificationDetails = array(
            'type' => 'Inspection',
            'message' => 'New Inspection is Scheduled',
            'property' => $propertyName,
            'date' => $inspectionDate,
            'time' => $inspectionTime,
            'route' => route('admin.inspection', $inspection->id),
        );

        $landlordNotificationDetails = array(
            'type' => 'Inspection',
            'message' => 'New Inspection is Scheduled',
            'property' => $propertyName,
            'date' => $inspectionDate,
            'time' => $inspectionTime,
            'route' => route('landlord.dashboard'),
        );

        $tenantNotificationDetails = array(
            'type' => 'Inspection',
            'message' => 'New Inspection is Scheduled',
            'property' => $propertyName,
            'date' => $inspectionDate,
            'time' => $inspectionTime,
            'route' => route('tenant.dashboard'),
        );

        Notification::send($admin, new InspectionNotification($adminNotificationDetails));
        Notification::send($landlord, new InspectionNotification($landlordNotificationDetails));
        Notification::send($tenant, new InspectionNotification($tenantNotificationDetails));

          return redirect()
                ->route('admin.inspection')
                ->withFlashMessage('Inspection Saved Successfully')
                ->withFlashType('success');
    }

    public function edit($id)
    {
        $page['page_title'] = 'Manage Inspection';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Edit Inspection';

        $inspection = Inspection::with('assignedTo', 'property', 'template')->findOrFail($id);

        $allAdmins = Admin::all();
        $allProperties = Property::where('status', 'Active')->get();
        $allTemplates = InspectionQuestionTitle::all();

        return view('admin.inspection.edit', compact('page', 'inspection', 'allAdmins', 'allProperties', 'allTemplates'));
    }

    public function update(Request $request, $id)
    {
        $inspection = Inspection::findOrFail($id);

        $data = $request->validate([
             'report_type'   => 'required|in:Inventory,Check In,Check Out,Property',
            'assign_to'     => 'required|exists:admins,id',
            'property_id'   => 'required|exists:properties,id',
            'template_id'   => 'required|exists:inspection_questions_title,id',
            'report_date'   => 'required|date_format:d/m/Y',
            'time_duration' => 'required|string|max:50',
        ]);

        $inspection->update([
            'report_type'   => $data['report_type'],
            'assigned_to'   => $data['assign_to'],
            'property_id'   => $data['property_id'],
            'template_id'   => $data['template_id'],
            'date'          => Carbon::createFromFormat('d/m/Y', $data['report_date'])->format('Y-m-d'),
            'time'          => $data['time_duration'],
        ]);

        return redirect()
                ->route('admin.inspection')
                ->withFlashMessage('Inspection Updated Successfully')
                ->withFlashType('success');
    }

    public function destroy($id)
    {
        $inspection = Inspection::findOrFail($id);

        $inspection->delete();

        return redirect()
                ->route('admin.inspection')
                ->withFlashMessage('Inspection Deleted Successfully')
                ->withFlashType('success');
    }

    public function search(Request $request)
    {
        $page['page_title']       = 'Manage Inspection';
        $page['page_parent']      = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current']     = 'Inspection';

        $keywords = $request->input('keywords', '');

        $inspections = Inspection::with('assignedTo', 'property', 'template')
            ->where(function($q) use ($keywords) {
                $q->where('report_type', 'like', "%{$keywords}%")
                ->orWhereHas('property', function($q2) use ($keywords) {
                    $q2->where('line1',    'like', "%{$keywords}%")
                        ->orWhere('city',    'like', "%{$keywords}%")
                        ->orWhere('county',  'like', "%{$keywords}%")
                        ->orWhere('postcode','like', "%{$keywords}%");
                })
                ->orWhereHas('template', function($q2) use ($keywords) {
                    $q2->where('title', 'like', "%{$keywords}%");
                })
                ->orWhereHas('assignedTo', function($q2) use ($keywords) {
                    $q2->where('name', 'like', "%{$keywords}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends(['keywords' => $keywords]);

        return view('admin.inspection.index', compact('page', 'inspections', 'keywords'));
    }

    public function surveyPage(Inspection $id)
    {
        $page['page_title']       = 'Manage Inspection';
        $page['page_parent']      = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current']     = 'Fill Inspection Survey';

        $inspection = $id;  
        
        $template = $inspection
            ->template()                      
            ->with([
                'inspectionQuestionSections' => function($q){
                    $q->with('inspectionQuestion')->orderBy('id');
                }
            ])->firstOrFail();

        return view('admin.inspection.fill', compact('inspection','template', 'page'));
    }

    public function surveySubmit(Request $request, Inspection $id)
    {
        $inspection = $id;
        $rules = [];
        foreach ($inspection->template
                    ->inspectionQuestionSections as $section) {
            foreach ($section->inspectionQuestion as $q) {
                $rules["responses.{$q->id}.answer"]  = 'required|in:yes,no';
                $rules["responses.{$q->id}.comment"] = 'nullable|string';
                $rules["photos.{$q->id}.*"]          = 'nullable|image|max:2048';
            }
        }
        $data = $request->validate($rules);

        DB::transaction(function() use ($inspection, $data) {
            $inspection->responses()->delete();

            // save each response
            foreach ($inspection->template
                    ->inspectionQuestionSections as $section) {
                foreach ($section->inspectionQuestion as $q) {
                    $respData = $data['responses'][$q->id];

                    $resp = $inspection->responses()->create([
                        'inspection_id' => $inspection->id,
                        'section_name'  => $section->section_name,
                        'question'      => $q->question,
                        'answer'        => $respData['answer'],
                        'comment'       => $respData['comment'] ?? null,
                    ]);

                    if (! empty($data['photos'][$q->id] ?? [])) {
                        foreach ($data['photos'][$q->id] as $file) {
                            $path = $file->store('inspection_photos', 'public');
                            $resp->photos()->create([
                                'inspection_answer_id' => $resp->id,
                                'path'                 => $path,
                            ]);
                        }
                    }
                }
            }
        });

        $inspection->status = 1;
        $inspection->save();

        return redirect()
                ->route('admin.inspection')
                ->withFlashMessage('Inspection Survey Filled Successfully')
                ->withFlashType('success');
    }

    public function downloadPdf(Inspection $id)
    {
        $inspection = $id;
        $inspection->load([
            'template.inspectionQuestionSections.inspectionQuestion',
            'responses.photos',
            'assignedTo',
            'property',
        ]);

        $sections = $inspection->responses
            ->sortBy('id')
            ->groupBy('section_name');

        $html = view('admin.inspection.inspection_report', [
            'inspection' => $inspection,
            'sections'   => $sections,
        ])->render();

        $pdf = Pdf::loadHTML($html)
        ->setOption('isRemoteEnabled', true)
        ->setOption('isPhpEnabled', true);

    // render it so we can grab the Dompdf instance
        $pdf->render();

        $canvas = $pdf->getDomPDF()->getCanvas();
        $font   = $pdf->getDomPDF()
                    ->getFontMetrics()
                    ->getFont('Helvetica', 'normal');
        $size   = 9;

        // position (in points) relative to bottom-right corner
        $x = $canvas->get_width()  - 200;
        $y = $canvas->get_height() - 30;

        $canvas->page_text(
        $x, $y,
        "Page {PAGE_NUM} of {PAGE_COUNT}    Tenant Initials: __________",
        $font, $size,
        [0.4, 0.4, 0.4]
        );

        $filename = 'Inspection_Report_'.$inspection->id.'.pdf';
        return $pdf->download($filename);
    }

}
