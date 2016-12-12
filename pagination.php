<!--================================PAGINATION BUTTONS================================================================ -->
                <nav aria-label="Page navigation" class="pagination pagination-right">
                  <ul class="pagination">
                    <?php
                      //PREPARATION FOR FIRST PAGE ELEMENT
                      $GETArrayHardCopy['page'] = 1; //setting to link to first page
                      if ($_GET['page'] == 1) //checking if current page is first
                      {
                        $disabledClass= ' class="disabled"';
                      }
                      else
                      {
                        $disabledClass= '';
                      }
                      // FIRST PAGE ELEMENT
                        echo
                      '<li'.$disabledClass.'>
                        <a href="?'.http_build_query($GETArrayHardCopy).'" aria-label="Previous">
                          <span aria-hidden="true">&laquo;</span>
                        </a>
                      </li>';

                      //PAGE NUMBER ELEMENTS
                      for ($i=1; $i <= $pages; $i++)
                      {
                        $GETArrayHardCopy['page'] = $i; //setting page value for http build querry
                        if($_GET['page'] == $i)
                          echo '<li class="active"><a href="?'.http_build_query($GETArrayHardCopy).'">'.$i.'</a></li>';
                        else
                          echo '<li><a href="?'.http_build_query($GETArrayHardCopy).'">'.$i.'</a></li>';
                      }
                      //PREPARATION TO LAST ELEMENT
                      $GETArrayHardCopy['page'] = $pages; //setting to link to last page
                      if ($_GET['page'] == $pages)  //checking if current page is last
                      {
                        $disabledClass= ' class="disabled"';
                      }
                      else
                      {
                        $disabledClass= '';
                      }
                      //LAST PAGE ELEMENT
                      echo
                        '<li'.$disabledClass.'>
                          <a href="?'.http_build_query($GETArrayHardCopy).'" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                          </a>
                        </li>';
                    ?>
                  </ul>
                </nav>
<!--================================PAGINATION BUTTONS END================================================================ -->
