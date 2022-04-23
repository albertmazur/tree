<h1>Drzewo kategori</h1>
<?php $tree=$params['list'][0];?>

<div class="tree-body">
   <div class="tree">
       <ul>
           <?php
                   echo $tree['id_rodzic'];
                   if($tree['id_rodzic']==null){
                        echo '<li><div data-id="'.$tree['id'].'" class="empty">'.$tree['nazwa'].'</div><button class="remove">remove</button>';
                   }
             ?>
       </ul>
   </div>
</div>