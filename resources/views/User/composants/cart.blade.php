  <!-- Cart -->
  <div class="w3-ch-sideBar w3-bar-block w3-card-2 w3-animate-right" style="display:none;right:0;"
  id="Cart">
  <div class="rightMenu-scroll">
      <div class="d-flex align-items-center justify-content-between slide-head py-3 px-3">
          <h4 class="cart_heading fs-md ft-medium mb-0">Products List</h4>
          <button onclick="closeCart()" class="close_slide" aria-label="{{ __('close') }}"><i class="ti-close"></i></button>
      </div>
      <div class="right-ch-sideBar">

          <div class="cart_select_items py-2">
              <!-- Single Item -->
              <div class="d-flex align-items-center justify-content-between br-bottom px-3 py-3">
                  <div class="cart_single d-flex align-items-center">
                      <div class="cart_selected_single_thumb">
                          <a href="#"><img src="https://via.placeholder.com/625x800" width="60" class="img-fluid" alt="Product Image" /></a>
                      </div>
                      <div class="cart_single_caption pl-2">
                          <h4 class="product_title fs-sm ft-medium mb-0 lh-1">Women Striped Shirt Dress</h4>
                          <p class="mb-2"><span class="text-dark ft-medium small">36</span>, <span
                                  class="text-dark small">Red</span></p>
                          <h4 class="fs-md ft-medium mb-0 lh-1">$129</h4>
                      </div>
                  </div>
                  <div class="fls_last"><button class="close_slide gray" aria-label="{{ __('close') }}"><i
                              class="ti-close"></i></button></div>
              </div>

              <!-- Single Item -->
              <div class="d-flex align-items-center justify-content-between br-bottom px-3 py-3">
                  <div class="cart_single d-flex align-items-center">
                      <div class="cart_selected_single_thumb">
                          <a href="#"><img src="https://via.placeholder.com/625x800" width="60" class="img-fluid" alt="Product Image" /></a>
                      </div>
                      <div class="cart_single_caption pl-2">
                          <h4 class="product_title fs-sm ft-medium mb-0 lh-1">Girls Floral Print Jumpsuit
                          </h4>
                          <p class="mb-2"><span class="text-dark ft-medium small">36</span>, <span
                                  class="text-dark small">Red</span></p>
                          <h4 class="fs-md ft-medium mb-0 lh-1">$129</h4>
                      </div>
                  </div>
                  <div class="fls_last"><button class="close_slide gray" aria-label="{{ __('close') }}"><i
                              class="ti-close"></i></button></div>
              </div>

              <!-- Single Item -->
              <div class="d-flex align-items-center justify-content-between px-3 py-3">
                  <div class="cart_single d-flex align-items-center">
                      <div class="cart_selected_single_thumb">
                          <a href="#"><img src="https://via.placeholder.com/625x800" width="60" class="img-fluid" alt="Product Image" /></a>
                      </div>
                      <div class="cart_single_caption pl-2">
                          <h4 class="product_title fs-sm ft-medium mb-0 lh-1">Girls Solid A-Line Dress</h4>
                          <p class="mb-2"><span class="text-dark ft-medium small">30</span>, <span
                                  class="text-dark small">Blue</span></p>
                          <h4 class="fs-md ft-medium mb-0 lh-1">$100</h4>
                      </div>
                  </div>
                  <div class="fls_last"><button class="close_slide gray" aria-label="{{ __('close') }}"><i
                              class="ti-close"></i></button></div>
              </div>

          </div>

          <div class="cart_action px-3 py-4">
              <div class="form-group">
                  <p class="mb-0 text-center"><span class="text-dark ft-medium">Subtotal</span> $500</p>
              </div>
              <a href="{{ route('cart') }}" class="btn btn-block btn-dark mb-2">View Cart</a>
          </div>

      </div>
  </div>
  </div>
</div>
</div>
