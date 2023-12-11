@extends('layouts.header')
<link rel="stylesheet" href="{{ asset('/storage/css/ViewAgent.css') }}" media="screen">
@section('content')
<div class="search-container">
    <div class="search-agent-bar">
        <form id="search-form" action="{{ route('SearchAgent') }}" method="GET">
            <input class="search-agent-input" type="text" name="search" placeholder="Search agent name...">
            <button class="search-agent-button" type="submit">
                <i class="fas fa-search"></i>
                <span class="search-text">Search</span>
            </button>
        </form>
    </div>
</div>
<div class="search-underline"></div>

@if (session('isSearching') === 'Yes')
@if ($results !== null)
@if (count($results) === 0)
<div class="agent-title">
    <a href="{{ route('AgentLists') }}" class="view-all">View All Agents</a>
</div>
<div class="no-record-container">
    <img class="no-record-image" src="{{ asset('storage/images/norecordfound.png') }}" alt="Description">
    <p class="no-record-message">We couldn't find anything matching your search for agent</p>
    <p class="suggestions">Suggestions:</p>
    <ul class="suggestion-list">
        <li>Make sure all spelling is correct</li>
        <li>Simplify your search</li>
        <li>Make sure your search contains no symbols</li>
    </ul>
</div>
@else
<div class="agent-title">
    <span class="agent-count">{{ $results->total() }} Agents Found</span>
    <a href="{{ route('AgentLists') }}" class="view-all">View All Agents</a>
</div>

@foreach ($results as $agent)
<div class="view-agents-container">
    <div class="row">
        <div class="col-md-6 part-a " style="background-image: url('{{ asset('storage/images/aBackground.png') }}');">
            <div class="agent-info">
                @if (!empty($agent->photo))
                <img src="{{ asset('storage/'. $agent->photo) }}" alt="Agent Photo">
                @else
                <img src="{{ asset('storage/users-avatar/agent.png') }}" alt="Default Image">
                @endif
                <p>{{$agent->agentName}}</p>
            </div>
        </div>
        <div class="col-md-6 part-b">
            <div class="contact-info">
                <p class="info-label">Contact Number:</p>
                <i class="fa fa-phone"></i>
                <span>{{ $agent->agentPhone }}</span>
            </div>
            <div class="contact-info">
                <p class="info-label">Email Address:</p>
                <i class="fa fa-envelope"></i>
                <span>{{ $agent->agentEmail }}</span>
            </div>

            <div class="contact-info">

                <p class="info-label">License Number:</p>
                <i class="fa fa-id-card"></i>
                <span>{{ $agent->licenseNum ?: '-' }}</span>

            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12 part-d">
            <div class="joined-info">
                <span>Joined {{ \Carbon\Carbon::parse($agent->registerDate)->diffForHumans() }}</span>
            </div>
            <div class="view-details">
                <a href="{{ route('AgentDetails', ['id' => $agent->agentID]) }}" class="view-details-button">View Agent
                    Details</a>
            </div>
        </div>
    </div>
</div>
@endforeach
<div class="row">
    <div class="col-md-12 d-flex justify-content-center result-page">
        {{ $results->appends(request()->query())->links() }}

    </div>
</div>
@endif
@else
<p>Agent Not Found</p>
@endif
@else
<div class="agent-title">
    <span class="agent-count">{{$agentList->total() }} Agents in Malaysia</span>
</div>

@foreach ($agentList as $agent)
<div class="view-agents-container">
    <div class="row">
        <div class="col-md-6 part-a " style="background-image: url('{{ asset('storage/images/aBackground.png') }}');">
            <div class="agent-info">
                @if (!empty($agent->photo))
                <img src="{{ asset('storage/'. $agent->photo) }}" alt="Agent Photo">
                @else
                <img src="{{ asset('storage/users-avatar/agent.png') }}" alt="Default Image">
                @endif
                <p>{{$agent->agentName}}</p>
            </div>
        </div>
        <div class="col-md-6 part-b">
            <div class="contact-info">
                <p class="info-label">Contact Number:</p>
                <i class="fa fa-phone"></i>
                <span>{{ $agent->agentPhone }}</span>
            </div>
            <div class="contact-info">
                <p class="info-label">Email Address:</p>
                <i class="fa fa-envelope"></i>
                <span>{{ $agent->agentEmail }}</span>
            </div>

            <div class="contact-info">

                <p class="info-label">License Number:</p>
                <i class="fa fa-id-card"></i>
                <span>{{ $agent->licenseNum ?: '-' }}</span>

            </div>

        </div>
    </div>
    <div class="row">

    </div>
    <div class="row">
        <div class="col-md-12 part-d">
            <div class="joined-info">
                <span>Joined {{ \Carbon\Carbon::parse($agent->registerDate)->diffForHumans() }}</span>
            </div>
            <div class="view-details">
                <a href="{{ route('AgentDetails', ['id' => $agent->agentID]) }}" class="view-details-button">View Agent
                    Details</a>
            </div>
        </div>
    </div>
</div>
@endforeach
<div class="row">
    <div class="col-md-12 d-flex justify-content-center result-page">
        {{ $agentList->onEachSide(1)->links() }}
    </div>
</div>
</div>
@endif
@endsection