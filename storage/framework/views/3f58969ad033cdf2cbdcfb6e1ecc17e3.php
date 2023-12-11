<link rel="stylesheet" href="<?php echo e(asset('/storage/css/ViewAgent.css')); ?>" media="screen">
<?php $__env->startSection('content'); ?>
<div class="search-container">
    <div class="search-agent-bar">
        <form id="search-form" action="<?php echo e(route('SearchAgent')); ?>" method="GET">
            <input class="search-agent-input" type="text" name="search" placeholder="Search agent name...">
            <button class="search-agent-button" type="submit">
                <i class="fas fa-search"></i>
                <span class="search-text">Search</span>
            </button>
        </form>
    </div>
</div>
<div class="search-underline"></div>

<?php if(session('isSearching') === 'Yes'): ?>
<?php if($results !== null): ?>
<?php if(count($results) === 0): ?>
<div class="agent-title">
    <a href="<?php echo e(route('AgentLists')); ?>" class="view-all">View All Agents</a>
</div>
<div class="no-record-container">
    <img class="no-record-image" src="<?php echo e(asset('storage/images/norecordfound.png')); ?>" alt="Description">
    <p class="no-record-message">We couldn't find anything matching your search for agent</p>
    <p class="suggestions">Suggestions:</p>
    <ul class="suggestion-list">
        <li>Make sure all spelling is correct</li>
        <li>Simplify your search</li>
        <li>Make sure your search contains no symbols</li>
    </ul>
</div>
<?php else: ?>
<div class="agent-title">
    <span class="agent-count"><?php echo e($results->total()); ?> Agents Found</span>
    <a href="<?php echo e(route('AgentLists')); ?>" class="view-all">View All Agents</a>
</div>

<?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="view-agents-container">
    <div class="row">
        <div class="col-md-6 part-a " style="background-image: url('<?php echo e(asset('storage/images/aBackground.png')); ?>');">
            <div class="agent-info">
                <?php if(!empty($agent->photo)): ?>
                <img src="<?php echo e(asset('storage/'. $agent->photo)); ?>" alt="Agent Photo">
                <?php else: ?>
                <img src="<?php echo e(asset('storage/users-avatar/agent.png')); ?>" alt="Default Image">
                <?php endif; ?>
                <p><?php echo e($agent->agentName); ?></p>
            </div>
        </div>
        <div class="col-md-6 part-b">
            <div class="contact-info">
                <p class="info-label">Contact Number:</p>
                <i class="fa fa-phone"></i>
                <span><?php echo e($agent->agentPhone); ?></span>
            </div>
            <div class="contact-info">
                <p class="info-label">Email Address:</p>
                <i class="fa fa-envelope"></i>
                <span><?php echo e($agent->agentEmail); ?></span>
            </div>

            <div class="contact-info">

                <p class="info-label">License Number:</p>
                <i class="fa fa-id-card"></i>
                <span><?php echo e($agent->licenseNum ?: '-'); ?></span>

            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12 part-d">
            <div class="joined-info">
                <span>Joined <?php echo e(\Carbon\Carbon::parse($agent->registerDate)->diffForHumans()); ?></span>
            </div>
            <div class="view-details">
                <a href="<?php echo e(route('AgentDetails', ['id' => $agent->agentID])); ?>" class="view-details-button">View Agent
                    Details</a>
            </div>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<div class="row">
    <div class="col-md-12 d-flex justify-content-center result-page">
        <?php echo e($results->appends(request()->query())->links()); ?>


    </div>
</div>
<?php endif; ?>
<?php else: ?>
<p>Agent Not Found</p>
<?php endif; ?>
<?php else: ?>
<div class="agent-title">
    <span class="agent-count"><?php echo e($agentList->total()); ?> Agents in Malaysia</span>
</div>

<?php $__currentLoopData = $agentList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="view-agents-container">
    <div class="row">
        <div class="col-md-6 part-a " style="background-image: url('<?php echo e(asset('storage/images/aBackground.png')); ?>');">
            <div class="agent-info">
                <?php if(!empty($agent->photo)): ?>
                <img src="<?php echo e(asset('storage/'. $agent->photo)); ?>" alt="Agent Photo">
                <?php else: ?>
                <img src="<?php echo e(asset('storage/users-avatar/agent.png')); ?>" alt="Default Image">
                <?php endif; ?>
                <p><?php echo e($agent->agentName); ?></p>
            </div>
        </div>
        <div class="col-md-6 part-b">
            <div class="contact-info">
                <p class="info-label">Contact Number:</p>
                <i class="fa fa-phone"></i>
                <span><?php echo e($agent->agentPhone); ?></span>
            </div>
            <div class="contact-info">
                <p class="info-label">Email Address:</p>
                <i class="fa fa-envelope"></i>
                <span><?php echo e($agent->agentEmail); ?></span>
            </div>

            <div class="contact-info">

                <p class="info-label">License Number:</p>
                <i class="fa fa-id-card"></i>
                <span><?php echo e($agent->licenseNum ?: '-'); ?></span>

            </div>

        </div>
    </div>
    <div class="row">

    </div>
    <div class="row">
        <div class="col-md-12 part-d">
            <div class="joined-info">
                <span>Joined <?php echo e(\Carbon\Carbon::parse($agent->registerDate)->diffForHumans()); ?></span>
            </div>
            <div class="view-details">
                <a href="<?php echo e(route('AgentDetails', ['id' => $agent->agentID])); ?>" class="view-details-button">View Agent
                    Details</a>
            </div>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<div class="row">
    <div class="col-md-12 d-flex justify-content-center result-page">
        <?php echo e($agentList->onEachSide(1)->links()); ?>

    </div>
</div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/tenant/AgentLists.blade.php ENDPATH**/ ?>