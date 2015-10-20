<script type="text/javascript" src="tree.js"></script>

<style>
.opened
{
background-image: url('ressource/image/icon/small/minus.gif');
background-repeat: no-repeat;
/*background-position: 0 0;*/
}

.closed
{
background-image: url('ressource/image/icon/small/plus.gif');
background-repeat: no-repeat;
}

.node
{
padding-left: 15px;
font-size: 12px;
cursor: pointer;
/*border: 1px solid blue;*/
}
</style>



<div id="loader">Ajax loading ...</div>

<div class="content panel" id="list">

<ul>
<?php require 'node.php'; ?>
</ul>




</div>


</div>


