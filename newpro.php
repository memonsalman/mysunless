  <?php 
     $idp=$_SESSION['UserID'];
     $statementp=$db->prepare("SELECT * FROM `ProductCategory` WHERE `createdfk`=:id And isactive=1");
     $statementp->bindValue(":id",$idp,PDO::PARAM_INT);
     $statementp->execute();
     $ProductCategoryListp = $statementp->fetchAll(PDO::FETCH_ASSOC);

     $idp=$_SESSION['UserID'];
     $statementp=$db->prepare("SELECT * FROM `ProductBrand` WHERE `createdfk`=:id And isactive=1");
     $statementp->bindValue(":id",$idp,PDO::PARAM_INT);
     $statementp->execute();
     $ProductBrandListp = $statementp->fetchAll(PDO::FETCH_ASSOC);
  ?>
  <div id="AddProductModalhead" class="modal fade" role="dialog" style="z-index: 1045">
                              <div class="modal-dialog">
                              <!-- Modal content-->

                                 <div class="modal-content">
                                    <div class="modal-header">
                                       <h4 class="modal-title">Add New Product</h4>
                                       <div class="Loader"></div>
                                       
                                       <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                       <form class="form-horizontal " autocomplete="off" action="" method="post" id="NewProducthead">
                                        <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                                       <input type="hidden" name="id" id="idpro" value="">
                                       <input type="hidden" name="clinetid" id="clientid" value="<?php echo @$ClientId;?>">
                                    <?php 
                                       if($usertype=='subscriber')
                                       {
                                    ?>
                                        <input type="hidden" name="sid" id="sid" value="<?php echo $_SESSION['UserID'];?>">
                                    <?php
                                       }
                                      else
                                      {
                                    ?>
                                        <input type="hidden" name="sid" id="sid" value="<?php echo $sid;?>">
                                    <?php
                                       }
                                    ?>
                                    <?php $_SESSION["ClientID"] = @$ClientId ;?>
                                    <div class="form-group">
                                       <label for="ProductCategory">Product Category * <span class="help"></span></label>
<select class="select2 m-b-10 select2-multiple form-control" data-placeholder="Select Category" id="ProductCategory" name="ProductCategory[]" multiple data-style="form-control btn-secondary">
                                       <?php
                                          foreach($ProductCategoryListp as $value)
                                          {
                                              echo '<option value="'.$value['Category'].'">'.$value['Category'].'</option>';
                                          }
                                       ?>
                                       </select>
                                    </div>

                                    <div class="form-group">
                                       <label for="ProductBrand">Brand * <span class="help"></span></label>
                                       <label class="pull-right"><a href="<?php echo base_url; ?>/AllProductBrand">Create New Brand</a></label>
<select class="select2 m-b-10 select2-multiple form-control" data-placeholder="Select Brand" id="ProductBrand" name="ProductBrand[]" multiple data-style="form-control btn-secondary">
                                       <?php
                                          foreach($ProductBrandListp as $value)
                                          {
                                               echo '<option value="'.$value['Brand'].'">'.$value['Brand'].'</option>';
                                          }
                                       ?>
                                       </select>
                                    </div>

                                    <div class="form-group">
                                       <label for="ProductTitle"><span class="help"> Product Name *</span></label>
           <input type="text" name="ProductTitle" class="form-control" placeholder="Product Name" value="" id="ProductTitle" maxlength="30">
                                    </div>
                                    <div class="form-group">
                                       <label for="ProductDescription">Product  Description *</label>
<textarea class="form-control" maxlength="150" id="ProductDescription" id="ProductDescription" name="ProductDescription" placeholder="Write Product  Description here.."  rows="5"></textarea>
                                    </div> 
                                    <div class="form-group">
                                       <label for="CompanyCost">Item Cost * <span class="help"></span></label>
                                       <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                    <!-- <p id="Price"> 0 </p> -->
<input type="text" id="CompanyCost" aria-label="Amount (to the nearest dollar)" name="CompanyCost" class="form-control" id="CompanyCost" placeholder="i.e. 100 " value="" >
                                <!-- <div class="input-group-append">
                                    <span class="input-group-text">.00</span>
                                </div> -->
                        </div>        
                                    </div>
                                    <div class="form-group">
                                       <label for="SellingPrice">Selling Price * <span class="help"></span></label>
                                       <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                    <!-- <p id="Price"> 0 </p> -->
 <input type="text" aria-label="Amount (to the nearest dollar)"  id="SellingPrice" name="SellingPrice" class="form-control" id="SellingPrice" placeholder="i.e. 100 " value="" >
                                <!-- <div class="input-group-append">
                                    <span class="input-group-text">.00</span>
                                </div> -->
                        </div>        
                                    </div>
                                

                                    <div class="form-group">
                                       <label for="ProductDescription">Discount in Percentage  ( % )</label>
                <input type="text" name="discountinparst" class="form-control" placeholder="Discount in % " value="" id="Commissioninperstantag" readonly>
                                    </div> 

                                    <!-- <span class="Commissioninperstantag"></span> -->
                                    
                                      <div class="form-group ">
                                       <label for="ProductImage">Product  Image (jpg/jpeg)<span class="help"></span></label>
                                       <div class="card">
                                          <div class="card-body">
                                            <input type="file"  name="ProductImage" class="dropify" id="ProductImage" />
                                          </div>
                                       </div>
                                    </div>
                                 <div class="form-group">
                                       <label for="CommissionAmount">No Of Product in Stock * <span class="help"></span></label>
<input type="text" id="NoofPorduct" name="NoofPorduct" class="form-control" placeholder="i.e. 10" value="<?php echo @$NoofPorduct;?>" >
                                    </div>
                                    <div class="form-group">
                    
<button type="submit" class="btn waves-effect waves-light btn-info" name="addProduct" id="addProduct"><i class="fa fa-check"></i> Submit Product</button>
                                 
<a href="" data-dismiss="modal" type="button" class="btn waves-effect waves-light btn-danger"><i class="fa fa-times"></i> Cancel Product</a>
                                    </div>
                                 </form>
                                    </div>
                                          <div class="modal-footer">
                                       <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                 </div>
                              </div>
                           </div>