
    <h1>Drzewo kategori</h1>
    <?php $tree=$params['list'][0];?>


    <div class="tree-body">
       <div class="tree">
           <ul>
               <?php
                       echo $tree['id_rodzic'];
                       if($tree['id_rodzic']==null){
                            echo '<li><div data-id="'.$tree['id'].'" class="empty">'.$tree['nazwa'].'</div><div class="remove">remove</div>';
                       }
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