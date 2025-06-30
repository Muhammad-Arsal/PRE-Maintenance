@extends('admin.partials.main')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/dashboard/vendors/css/forms/selects/select2.css') }}" />
<style>
  /* Modern Card + Input Styling */
  .card { border-radius: .5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
  .section-card, .question-card {
    border: 1px solid #e0e0e0; border-radius: .5rem; padding: 1.5rem; margin-bottom: 1rem;
    background: #fff;
  }
  .btn-add { margin-top: .5rem; }
  .remove-btn { cursor: pointer; color: #e74c3c; font-size: 1.2rem; }
</style>
@endsection

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2 breadcrumb-new">
            <h3 class="content-header-title mb-0 d-inline-block">{{ $page['page_title'] }}</h3>
            <div class="row breadcrumbs-top d-inline-block">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ $page['page_parent_link'] }}"
                                class="theme-color">{{ $page['page_parent'] }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $page['page_current'] }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>  
    </div>
    <div class="content-body">
        @include('admin.partials.flashes')
        
        {{-- BEGIN: Inspection Questions Form --}}
        <form method="POST" action="{{ route('admin.settings.inspectionQuestions.store') }}">
            @csrf

            <div class="card p-2 mb-4">
                <label class="h5">Inspection Questions Title</label>
                <input type="text" name="title" class="form-control" placeholder="Enter title" required>
            </div>

            <div id="sections-container"></div>

            <div class="text-center mb-5">
                <button id="add-section" type="button" class="btn btn-outline-primary btn-add">
                    + Add Section
                </button>
            </div>

            <div class="text-right">
                <button style="background: #0b1d3f; color: #fff;" type="submit" class="btn">Save Inspection</button>
            </div>
        </form>
        {{-- END: Inspection Questions Form --}}

    </div>
@endsection

@section('js')
<script src="{{ asset('/dashboard/vendors/js/forms/select/select2.js') }}" type="text/javascript"></script>
<script>
    $(function(){
      let sectionIdx = 0;

      function sectionTemplate(idx){
        return `
          <div class="section-card" data-section="${idx}">
            <div class="d-flex justify-content-between align-items-center">
              <h6>Section <span>${idx+1}</span></h6>
              <span class="remove-btn" onclick="removeSection(${idx})">&times;</span>
            </div>
            <input type="text" name="sections[${idx}][name]" class="form-control mb-3"
                   placeholder="Section Name" required>
            <div class="questions-container" data-section="${idx}"></div>
            <button type="button" class="btn btn-sm btn-outline-secondary btn-add"
                    onclick="addQuestion(${idx})">
              + Add Question
            </button>
          </div>
        `;
      }

      function questionTemplate(sIdx, qIdx){
        return `
          <div class="question-card d-flex align-items-center" data-question="${qIdx}">
            <input type="text" name="sections[${sIdx}][questions][${qIdx}][question_text]"
                   class="form-control mr-2" placeholder="Question text" required>
            <span class="remove-btn" onclick="removeQuestion(${sIdx},${qIdx})">&times;</span>
          </div>
        `;
      }

      $('#add-section').on('click', function(){
        $('#sections-container').append(sectionTemplate(sectionIdx));
        window[`questionCount_${sectionIdx}`] = 0;
        sectionIdx++;
      });

      window.removeSection = function(sIdx){
        $(`.section-card[data-section=${sIdx}]`).remove();
      };

      window.addQuestion = function(sIdx){
        let qCount = window[`questionCount_${sIdx}`]++;
        $(`.questions-container[data-section=${sIdx}]`)
          .append(questionTemplate(sIdx, qCount));
      };

      window.removeQuestion = function(sIdx, qIdx){
        $(`.section-card[data-section=${sIdx}]`)
          .find(`.question-card[data-question=${qIdx}]`)
          .remove();
      };

    });
</script>
@endsection
