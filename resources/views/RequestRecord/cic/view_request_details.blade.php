@extends('layouts.app')
@extends('layouts.header')

@section('request-content')
    <div class="container mb-3">
        <div class="row mb-3">
            @if (Auth::user()->account_role == 'cic')
                <form class="mb-3" action="{{ route('cic.request') }}" method="get">
                    <button class="btn btn-success btn-sm"><i class="bi bi-arrow-bar-left"></i> BACK</button>
                </form>
            @elseif (Auth::user()->account_role == 'student')
                <form class="mb-3" action="{{ route('stud.request') }}" method="get">
                    <button class="btn btn-success btn-sm"><i class="bi bi-arrow-bar-left"></i> BACK</button>
                </form>
            @endif
            <div class="border-start border-danger border-4 mb-2">
                <h4 class="ms-1 my-auto">STUDENT INFORMATION</h4>
            </div>
            <div class="row align-items-center mb-3 w-50 ms-2">
                <img class="col-3 img-fluid rounded-circle student-pic"
                    src="{{ asset('storage/' . $picturePath->document_loc) }}">
                <div class="col-9">
                    <span class="h4 fw-bold">{{ $student->last_name }}, {{ $student->first_name }}
                        {{ mb_substr($student->middle_name, 0, 1) . '.' }}</span>
                    <br>
                    <span>{{ $student->student_id }}</span>
                    <br>
                    <span>{{ $student->course_name }}</span>
                    <br>
                    <span>Request ID: {{ $requestedDocumentDetails->request_id }}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="border-start border-danger border-4 mb-3">
                <h4 class="ms-1 my-auto">REQUEST DETAILS</h4>
            </div>
            <div class="col ms-3">
                <table class="table">
                    <thead>
                        <th class="custom-th bg-danger">Document Name</th>
                        <th class="custom-th bg-danger">Quantity</th>
                        <th class="custom-th bg-danger">Subtotal</th>
                    </thead>
                    <tbody>
                        @if ($requestedDocumentDetails->certificate != null)
                            @foreach ($requestedDocumentDetails->certificate as $certs)
                                @foreach ($certs as $key => $value)
                                    <tr class="custom-tr">
                                        <td class="custom-td">{{ $key }} </td>
                                        <td class="custom-td">{{ $value }}</td>
                                        @if ($value == 1)
                                            <td class="custom-td"> 110</td>
                                        @else
                                            <td class="custom-td">{{ $value * 110 }} </td>
                                        @endif

                                    </tr>
                                @endforeach
                            @endforeach
                        @endif
                        @if ($requestedDocumentDetails->diploma != null)
                            <tr class="custom-tr">
                                @foreach ($requestedDocumentDetails->diploma as $diploma)
                                    <td class="custom-td">
                                        {{ $diploma }}
                                    </td>
                                    <td class="custom-td"> 1</td>
                                    @if ($diploma == 'Bachelor/Law Degree')
                                        <td class="custom-td"> 516</td>
                                    @elseif ($diploma == 'Masteral Degree')
                                        <td class="custom-td"> 729</td>
                                    @elseif ($diploma == 'TESDA')
                                        <td class="custom-td"> 302</td>
                                    @elseif ($diploma == 'Caregiving')
                                        <td class="custom-td"> 250</td>
                                    @endif
                                @endforeach
                            </tr>
                        @endif
                        @if ($requestedDocumentDetails->transcript_of_record != null)
                            @foreach ($requestedDocumentDetails->transcript_of_record as $records)
                                <tr class="custom-tr">
                                    <td class="custom-td"> Transcript of Record </td>
                                    <td class="custom-td"> {{ $records }} </td>
                                    <td class="custom-td"> {{ $records * 110 }} </td>
                                </tr>
                            @break
                        @endforeach
                    @endif
                    @if ($requestedDocumentDetails->copy_of_grades != null)
                        @foreach ($requestedDocumentDetails->copy_of_grades as $grades)
                            <tr class="custom-tr">
                                <td class="custom-td"> Copy of Grades </td>
                                <td class="custom-td"> {{ $grades }} </td>
                                <td class="custom-td"> {{ $grades * 110 }} </td>
                            </tr>
                        @break
                    @endforeach
                @endif
                @if ($requestedDocumentDetails->authentication != null)
                    <tr class="custom-tr">
                        @foreach ($requestedDocumentDetails->authentication as $authentication)
                            <td class="custom-td">
                                {{ $authentication }}
                            </td>
                            <td class="custom-td"> 1</td>
                            <td class="custom-td"> 89.50</td>
                        @endforeach
                    </tr>
                @endif
                @if ($requestedDocumentDetails->photocopy != null)
                    <tr class="custom-tr">
                        @foreach ($requestedDocumentDetails->photocopy as $copies)
                            <td class="custom-td">{{ $copies }}</td>
                            <td class="custom-td">1</td>
                            @if ($requestedDocumentDetails->photocopy['photocopyType'] == 'colored')
                                <td class="custom-td">20</td>
                            @else
                                <td class="custom-td">1.20</td>
                            @endif
                        @break
                    @endforeach
                </tr>
            @endif
        </tbody>
    </table>

    <div class=" text-xl text-danger">
        Total Fee: {{ $requestedDocumentDetails->total_fee }}
    </div>

</div>

</div>

<div class="col mt-5 text-center">

@if ($requestInfo->status == 'IN PROGRESS' && Auth::user()->account_role == 'cic')
    <button class="btn btn-success btn-sm " data-bs-toggle="modal" data-bs-target="#accept-request-modal">ACCEPT
        REQUEST</button>
    <button class="btn btn-danger btn-sm " data-bs-toggle="modal" data-bs-target="#delete-request-modal">REJECT
        REQUEST</button>
@endif

@if ($requestInfo->status == 'SET FOR RELEASE' && Auth::user()->account_role == 'cic')
    <button class="btn btn-success btn-sm " data-bs-toggle="modal"
        data-bs-target="#accept-request-modal">COMPLETE
        REQUEST</button>
@endif

@if ($requestInfo->status == 'IN PROGRESS' && Auth::user()->account_role == 'student')
    <button class="btn btn-danger btn-sm " data-bs-toggle="modal" data-bs-target="#delete-request-modal">CANCEL
        REQUEST</button>
@endif

</div>

</div>
<script src="{{ asset('js/main.js') }}"></script>
<!--Modal for Rejecting Request-->
@extends('layouts.modals.delete_student_request', ['routeName' => 'cic.rejectRequest', 'request_id' => $requestedDocumentDetails, 'request_status' => $requestInfo])
<!--Modal for Accepting Request-->
@extends('layouts.modals.accept_student_request', ['routeName' => 'cic.acceptRequest', 'request_id' => $requestedDocumentDetails, 'request_status' => $requestInfo])
@endsection