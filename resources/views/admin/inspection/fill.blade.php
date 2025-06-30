@extends('admin.partials.main')

@section('css')
<link rel="stylesheet" href="{{ asset('/dashboard/vendors/css/forms/selects/select2.css') }}" />
<style>
  /* Theme colors */
  :root { --theme-primary: #041e41; --theme-secondary: #00bed6; }
  /* Panels */
  .wizard-panel { animation: fadeIn .3s ease-in-out; }
  @keyframes fadeIn { from {opacity:0;transform:translateY(10px);} to{opacity:1;transform:translateY(0);} }
  /* Section & question cards */
  .section-card { margin-bottom:1.5rem; }
  .section-card .card-header { background:var(--theme-secondary)!important; color:#fff; }
  .question-card { padding:1rem; border:1px solid #e0e0e0; border-radius:.5rem; background:#fff; margin-bottom:1rem; }
  .question-card label { font-weight:600; display:block; margin-bottom:.5rem; }
  /* Uniform inputs */
  .form-control, .custom-select { min-height:2.5rem; padding:.5rem .75rem; }
  .form-control-file { padding:.375rem .75rem; }
  /* Controls bar */
  .wizard-controls { margin-top:1rem; display:flex; justify-content:flex-end; }
  .wizard-controls .btn { min-width:6rem; margin-left:.5rem; }
  .btn-prev { background:var(--theme-secondary); color:#fff; }
  .btn-next, .btn-finish { background:var(--theme-primary); color:#fff; }
  @media(max-width:576px){ .form-row .col-md-2, .form-row .col-md-6, .form-row .col-md-4 { width:100%; display:block; margin-bottom:.75rem; } }
</style>
@endsection

@section('content')
  <div class="content-header row">
    <div class="content-header-left col-12 mb-2 breadcrumb-new">
      <h3 class="content-header-title mb-0 d-inline-block">{{ $page['page_title'] }}</h3>
      <div class="breadcrumb-wrapper d-inline-block">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ $page['page_parent_link'] }}" class="theme-color">{{ $page['page_parent'] }}</a></li>
          <li class="breadcrumb-item active">{{ $page['page_current'] }}</li>
        </ol>
      </div>
    </div>
  </div>
  <div class="content-body">
    <form id="fillSurvey" method="POST" action="{{ route('admin.inspection.survey.submit', $inspection->id) }}" enctype="multipart/form-data">
      @csrf

      <!-- Panels -->
      @foreach($template->inspectionQuestionSections as $i => $section)
        <div class="wizard-panel @if($i!==0) d-none @endif" data-step="{{ $i }}">
          <div class="section-card">
            <div class="card shadow-sm">
              <div class="card-header"><h5 class="mb-0">{{ $section->section_name }}</h5></div>
              <div class="card-body">
                @foreach($section->inspectionQuestion as $q)
                  <div class="question-card">
                    <label for="ans_{{ $q->id }}">{{ $q->question }}</label>
                    <div class="form-row align-items-start">
                      <div class="col-md-2">
                        <select id="ans_{{ $q->id }}" name="responses[{{ $q->id }}][answer]" class="custom-select" required>
                          <option value="">Select</option><option value="yes">Yes</option><option value="no">No</option>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <textarea name="responses[{{ $q->id }}][comment]" class="form-control" rows="2" placeholder="Comment (optional)"></textarea>
                      </div>
                      <div class="col-md-4">
                        <input type="file" name="photos[{{ $q->id }}][]" multiple class="form-control-file">
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
          <div class="wizard-controls">
            @if($i === 0)
              <a href="{{ route('admin.inspection') }}" class="btn btn-prev"> Cancel</a>
              @if(count($template->inspectionQuestionSections) > 1)
                <button type="button" class="btn btn-next" onclick="nextStep()">Next</button>
              @else
                <button type="submit" class="btn btn-finish">Finish</button>
              @endif
            @elseif($i < count($template->inspectionQuestionSections) - 1)
              <button type="button" class="btn btn-prev" onclick="prevStep()">Back</button>
              <button type="button" class="btn btn-next" onclick="nextStep()">Next</button>
            @else
              <button type="button" class="btn btn-prev" onclick="prevStep()">Back</button>
              <button type="submit" class="btn btn-finish">Finish</button>
            @endif
          </div>
        </div>
      @endforeach
    </form>
  </div>
@endsection

@section('js')
<script src="{{ asset('/dashboard/vendors/js/forms/select/select2.js') }}"></script>
<script>
  let step = 0;
  const panels = document.querySelectorAll('.wizard-panel');
  function showStep(n) { panels.forEach((p,i)=>p.classList.toggle('d-none',i!==n)); step=n; }
  function nextStep(){ if(step+1<panels.length) showStep(step+1); }
  function prevStep(){ if(step>0) showStep(step-1); }
</script>
@endsection
