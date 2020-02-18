<?php 
use Illuminate\Support\Facades\Session;

print_r($request->fullUrl()); ?>
<hr />
REQUEST: <?php print_r($request->all()); ?>
<hr />
<?php 
$data = Session::all();
if($data != null): ?>
SESSION: <?php print_r($data); ?>
<hr />
<?php endif; ?>
ERROR: <?php print_r($exception->getMessage()); ?><br />
TRACE: <?php print_r($exception->getTraceAsString()); ?>