@extends('layouts.app')

@section('content')

<h2 class="mb-3"><i class="fas fa-comments fa-fw mr-sm-3"></i> Customer Feedback Questionnaire</h2>

{!! Form::Open(['url' => 'feedback', 'class' => 'mb-5', 'autocomplete' => 'off']) !!}

<table class="table table-bordered mb-5">
    <tbody>
        <tr class="bg-light text-nowrap">
            <td></td>
            <td></td>
            <td class="text-center">Very satisfied</td>
            <td class="text-center">Satisfied</td>
            <td class="text-center">Neither satisfied nor dissatisfied</td>
            <td class="text-center">Dissatisfied</td>
            <td class="text-center">Very dissatisfied</td>
            <td class="text-center">Not applicable</td>
        </tr>
        <tr>
            <td class="font-weight-bold">1.</td>
            <td class="font-weight-bold">Did we meet your delivery expectations?</td>
            <td class="text-center"><input type="radio" name="question_1" value="Very satisfied"></td>
            <td class="text-center"><input type="radio" name="question_1" value="Satisfied"></td>
            <td class="text-center"><input type="radio" name="question_1" value="Neither satisfied nor dissatisfied"></td>
            <td class="text-center"><input type="radio" name="question_1" value="Dissatisfied"></td>
            <td class="text-center"><input type="radio" name="question_1" value="Very dissatisfied"></td>
            <td class="text-center"><input type="radio" name="question_1" value="Not applicable"></td>
        </tr>
        <tr>
            <td class="font-weight-bold">2.</td>
            <td class="font-weight-bold">Our communication with you met your needs?</td>
            <td class="text-center"><input type="radio" name="question_2" value="Very satisfied"></td>
            <td class="text-center"><input type="radio" name="question_2" value="Satisfied"></td>
            <td class="text-center"><input type="radio" name="question_2" value="Neither satisfied nor dissatisfied"></td>
            <td class="text-center"><input type="radio" name="question_2" value="Dissatisfied"></td>
            <td class="text-center"><input type="radio" name="question_2" value="Very dissatisfied"></td>
            <td class="text-center"><input type="radio" name="question_2" value="Not applicable"></td>
        </tr>
        <tr>
            <td class="font-weight-bold">3.</td>
            <td class="font-weight-bold">Did you receive value for money?</td>
            <td class="text-center"><input type="radio" name="question_3" value="Very satisfied"></td>
            <td class="text-center"><input type="radio" name="question_3" value="Satisfied"></td>
            <td class="text-center"><input type="radio" name="question_3" value="Neither satisfied nor dissatisfied"></td>
            <td class="text-center"><input type="radio" name="question_3" value="Dissatisfied"></td>
            <td class="text-center"><input type="radio" name="question_3" value="Very dissatisfied"></td>
            <td class="text-center"><input type="radio" name="question_3" value="Not applicable"></td>
        </tr>
        <tr class="bg-light text-nowrap">
            <td></td>
            <td></td>
            <td class="text-center">Very helpful</td>
            <td class="text-center">Helpful</td>
            <td class="text-center">Neither</td>
            <td class="text-center">Not so helpful</td>
            <td class="text-center">Not at all helpful</td>
            <td class="text-center">Not applicable</td>
        </tr>
        <tr>
            <td class="font-weight-bold">4.</td>
            <td class="font-weight-bold">How did you find our office staff?</td>
            <td class="text-center"><input type="radio" name="question_4" value="Very helpful"></td>
            <td class="text-center"><input type="radio" name="question_4" value="Helpful"></td>
            <td class="text-center"><input type="radio" name="question_4" value="Neither"></td>
            <td class="text-center"><input type="radio" name="question_4" value="Not so helpful"></td>
            <td class="text-center"><input type="radio" name="question_4" value="Not at all helpful"></td>
            <td class="text-center"><input type="radio" name="question_4" value="Not applicable"></td>
        </tr>
        <tr>
            <td class="font-weight-bold">5.</td>
            <td class="font-weight-bold">How did you find our driver?</td>
            <td class="text-center"><input type="radio" name="question_5" value="Very helpful"></td>
            <td class="text-center"><input type="radio" name="question_5" value="Helpful"></td>
            <td class="text-center"><input type="radio" name="question_5" value="Neither"></td>
            <td class="text-center"><input type="radio" name="question_5" value="Not so helpful"></td>
            <td class="text-center"><input type="radio" name="question_5" value="Not at all helpful"></td>
            <td class="text-center"><input type="radio" name="question_5" value="Not applicable"></td>
        </tr>
        <tr class="bg-light text-nowrap">
            <td></td>
            <td></td>
            <td class="text-center">Considerable interest</td>
            <td class="text-center">Moderate interest</td>
            <td class="text-center">Some interest</td>
            <td class="text-center">Little interest</td>
            <td class="text-center">No interest</td>
            <td class="text-center">Not applicable</td>
        </tr>
        <tr>
            <td class="font-weight-bold">6.</td>
            <td class="font-weight-bold">Would you be interested in hearing more about our courier, air, sea or road transport services?</td>
            <td class="text-center"><input type="radio" name="question_6" value="Considerable interest"></td>
            <td class="text-center"><input type="radio" name="question_6" value="Moderate interest"></td>
            <td class="text-center"><input type="radio" name="question_6" value="Some interest"></td>
            <td class="text-center"><input type="radio" name="question_6" value="Little interest"></td>
            <td class="text-center"><input type="radio" name="question_6" value="No interest"></td>
            <td class="text-center"><input type="radio" name="question_6" value="Not applicable"></td>
        </tr>
        <tr>
            <td class="font-weight-bold">7.</td>
            <td class="font-weight-bold">Would you be interested in hearing more about our warehousing services?</td>
            <td class="text-center"><input type="radio" name="question_7" value="Considerable interest"></td>
            <td class="text-center"><input type="radio" name="question_7" value="Moderate interest"></td>
            <td class="text-center"><input type="radio" name="question_7" value="Some interest"></td>
            <td class="text-center"><input type="radio" name="question_7" value="Little interest"></td>
            <td class="text-center"><input type="radio" name="question_7" value="No interest"></td>
            <td class="text-center"><input type="radio" name="question_7" value="Not applicable"></td>
        </tr>
        <tr class="bg-light text-nowrap">
            <td></td>
            <td></td>
            <td class="text-center">Strongly agree</td>
            <td class="text-center">Agree</td>
            <td class="text-center">Neither agree or disagree</td>
            <td class="text-center">Disagree</td>
            <td class="text-center">Strongly disagree</td>
            <td class="text-center">Not applicable</td>
        </tr>
        <tr>
            <td class="font-weight-bold">8.</td>
            <td class="font-weight-bold">You would use us again?</td>
            <td class="text-center"><input type="radio" name="question_8" value="Strongly agree"></td>
            <td class="text-center"><input type="radio" name="question_8" value="Agree"></td>
            <td class="text-center"><input type="radio" name="question_8" value="Neither agree or disagree"></td>
            <td class="text-center"><input type="radio" name="question_8" value="Disagree"></td>
            <td class="text-center"><input type="radio" name="question_8" value="Strongly disagree"></td>
            <td class="text-center"><input type="radio" name="question_8" value="Not applicable"></td>
        </tr>
        <tr>
            <td class="font-weight-bold">9.</td>
            <td class="font-weight-bold">Would you recommend us to another company?</td>
            <td class="text-center"><input type="radio" name="question_9" value="Strongly agree"></td>
            <td class="text-center"><input type="radio" name="question_9" value="Agree"></td>
            <td class="text-center"><input type="radio" name="question_9" value="Neither agree or disagree"></td>
            <td class="text-center"><input type="radio" name="question_9" value="Disagree"></td>
            <td class="text-center"><input type="radio" name="question_9" value="Strongly disagree"></td>
            <td class="text-center"><input type="radio" name="question_9" value="Not applicable"></td>
        </tr>
        <tr>
            <td class="font-weight-bold">10.</td>
            <td class="font-weight-bold">Our overall service was better than others you have tried?</td>
            <td class="text-center"><input type="radio" name="question_10" value="Strongly agree"></td>
            <td class="text-center"><input type="radio" name="question_10" value="Agree"></td>
            <td class="text-center"><input type="radio" name="question_10" value="Neither agree or disagree"></td>
            <td class="text-center"><input type="radio" name="question_10" value="Disagree"></td>
            <td class="text-center"><input type="radio" name="question_10" value="Strongly disagree"></td>
            <td class="text-center"><input type="radio" name="question_10" value="Not applicable"></td>
        </tr>
</table>

<div class="text-center">
    <button type="submit" class="btn btn-primary btn-lg">Send Feedback</button>
</div>


{!! Form::Close() !!}

@endsection