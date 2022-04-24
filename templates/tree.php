<div class="col-8">
    <h1>Drzewo kategori</h1>
    <div class="tree-body">
       <div class="tree">
           <ul><li>
               <?php
                if(count($params['list'])>0): $tree=$params['list'][0];
                    if($tree!=null){
                        echo '<div data-id="'.$tree['id'].'" class="list">'.$tree['nazwa'].'</div><button class="removeFirst">Usuń</button> <button class="edit">Edytuj</button>';
                    }
                 ?>
               <?php else: ?>
                <button class="addFirst">+</button>
               <?php endif; ?>
           </li></ul>
       </div>
    </div>
</div>