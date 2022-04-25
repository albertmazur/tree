<div class="col-8">
    <h1 class="text-center">Drzewo kategori</h1>
    <div class="tree-body">
       <div class="tree">
           <ul><li>
               <?php
                if(count($params['list'])>0): $tree=$params['list'][0];
                    if($tree!=null){
                        echo '<div data-id="'.$tree['id'].'" class="list">'.$tree['nazwa'].'</div><button class="removeFirst btn btn-danger m-2">Usuń</button> <button class="edit btn btn-success">Edytuj</button>';
                    }
                 ?>
               <?php else: ?>
                <button class="addFirst btn btn-secondary">+</button>
               <?php endif; ?>
           </li></ul>
       </div>
    </div>
</div>