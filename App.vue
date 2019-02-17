<!-- The root component -->
<template>
  <div>
    <!--sidebar start-->
    <aside>
      <div id="sidebar" class="nav-collapse">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">
            <router-link tag="li" to="/">
                <a><i class="fa fa-bullhorn"></i> Option 1</a>
            </router-link>

            <router-link tag="li" to="/timezones">
                <a><i class="fa fa-clock-o"></i> Option 2</a>
            </router-link>

            <router-link tag="li" to="/speakers">
                <a><i class="fa fa-users"></i> Option 3</a>
            </router-link>

            <router-link tag="li" to="/organisations">
                <a><i class="fa fa-building"></i> Option 4</a>
            </router-link>
        </ul>
        <!-- sidebar menu end-->
      </div>
    </aside>
    <!--sidebar end-->
    <section id="main-content">
      <section class="wrapper site-min-height">
        <router-view></router-view>
      </section>
      <!--/wrapper -->
    </section>
    <!-- /MAIN CONTENT -->
  </div>
</template>
