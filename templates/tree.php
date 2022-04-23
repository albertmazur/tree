<div class="col-8">
    <h1>Drzewo kategori</h1>
    <div class="tree-body">
       <div class="tree">
           <ul><li>
               <?php
                if(count($params['list'])>0): $tree=$params['list'][0];
                    if($tree!=null){
                        echo '<div data-id="'.$tree['id'].'" class="empty">'.$tree['nazwa'].'</div><button class="remove">remove</button> <button class="edit">Edit</button>';
                    }
                 ?>
               <?php else: ?>
                <button class="addFirst">+</button>
               <?php endif; ?>
           </li></ul>
       </div>
    </div>
</div>