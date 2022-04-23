<div class="col-8">
    <h1>Drzewo kategori</h1>
    <?php $tree=$params['list'][0];?>


    <div class="tree-body">
       <div class="tree">
           <ul>
               <?php
                    $a = 0;
                       echo $tree['id_rodzic'];
                       if($tree['id_rodzic']==null){
                            echo '<li><div class="empty" data-id="'.$tree['id'].'">'.$tree['nazwa'].'</div><div class="remove">remove</div>';
                            echo '<ul>';
                            $a = $tree['id'];
                       }
                        echo '</ul>';
                 ?>
               <!--
               <li>
                   <div class="empty"></div><div class="remove">remove</div>
                   <ul>
                       <li><div class="empty"></div><div class="remove">remove</div>
                           <ul>
                               <li><div class="empty"></div><div class="remove">remove</div></li>
                               <li><div class="empty"></div><div class="remove">remove</div></li>
                               <li><div class="empty"></div><div class="remove">remove</div></li>
                               <li><div class="add">+</div></li>
                           </ul>
                       </li>
                       <li><div class="empty"></div><div class="remove">remove</div>
                           <ul>
                               <li><div class="empty"></div><div class="remove">remove</div></li>
                               <li><div class="add">+</div></li>
                           </ul>
                       </li>
                       <li><div class="empty"></div><div class="remove">remove</div></li>
                       <li><div class="add">+</div></li>
                   </ul>
               </li>
               -->
           </ul>
       </div>
    </div>
</div>