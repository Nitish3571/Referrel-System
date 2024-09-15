@extends('layouts.contentLayout')

@section('content-section')

    <h6 style="cursor: pointer" class="copy" data-code="{{ Auth::user()->referral_code }}"><span
            class="fa fa-copy mr-1"></span>Copy Referral Link</h6>
    <h2 class="mb-4" style="float: left">Dashboad</h2>
    <h2 class="mb-4" style="float: right">{{ $networkCount * 10 }} Points</h2>

    <table class="table">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Verified</th>
            </tr>
        </thead>
        <tbody>
            @if (count($networkData) > 0)
                @php $x = 1; @endphp
                @foreach ($networkData as $network)
                @if (!empty($network->user->email))
                {{-- {{var_dump($network)}} --}}
                    <tr>
                        <td>{{ $x++ }}</td>
                        <td>{{ $network->user->name }}</td>
                        <td>{{ $network->user->email }}</td>
                        <td>
                            @if ($network->user->is_varified == 1)
                                <span class="badge badge-success">Verified</span>
                            @else
                                <span class="badge badge-danger">Not Verified</span>
                            @endif
                        </td>
                    </tr>
                    @endif
                @endforeach
            @else
                <tr>
                    <td colspan="4">No Referral Found!</td>
                </tr>
            @endif
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            $('.copy').click(function() {
                // $(this).parent().prepend('<span class="copied_text">copied</span>')

                // var code = $(this).attr('data-code');
                // var url = "{{URL::to('/')}}/referral-register?ref="+code;

                // var $temp = $("<input>");
                // $("body").append($temp);
                // $temp.val(url).select();
                // document.execCommand("copy");
                // $temp.remove();


                // setTimeout(() => {
                //     $('.copied_text').remove();
                // }, 2000);

                var code = $(this).attr('data-code');
                var url = "{{URL::to('/')}}/referral-register?ref="+code;
                navigator.clipboard.writeText(url).then(function() {
                    alert('Referral Link Copied!');
                }, function(err) {
                    console.error('Async: Could not copy text: ', err);
                });
            })
        })
    </script>

@endsection
