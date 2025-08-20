<!--header start-->
      <header id="header" class="uis-header uis-header--green">
         <div class="container">
            <div class="row">
               <div class="col-md-12 top-head">
                  <div class="navbar-header">
                     <!--logo start-->
                     <a href="<?=base_index();?>" class="navbar-brand"> <span class="logo" style="margin-right:10px"><img src="<?=getPengaturan('logo');?>" alt=""/ style=" width: 64px;    height: 47px;
    width: auto;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(36, 119, 74, 0.18);
    background: #fff;
    padding: 2px;"></span> <?=getPengaturan('app_name');?></b></a>

        </a>
                     <!--logo end-->
                  </div>
                  <div class="search-dropdown dropdown pull-right visible-xs">

                  </div>
                  <div class="navbar-collapse nav-responsive-disabled">
                     <!--search start-->
                     <!--search end-->
                     <!--notification start-->
                     <ul class="nav navbar-nav navbar-right">
                        
                        <li class="dropdown dropdown-usermenu">
                           <a href="#" class=" dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                              <div class="user-avatar"><img src="<?=$db->fetchSingleRow('sys_users','id',$_SESSION['id_user'])->foto_user?>" alt="..."></div> <span class="hidden-sm hidden-xs" style="color:#fff"><?=ucwords($db->fetchSingleRow('sys_users','id',$_SESSION['id_user'])->full_name)?></span>
                              <!--<i class="fa fa-angle-down"></i>--><span class="caret hidden-sm hidden-xs"></span> </a>
                           <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                              <li><a href="<?=base_index();?>profil"><i class="fa fa-user"></i>  Profile</a></li>
                              <li><a href="<?=base_admin();?>logout.php"><i class="fa fa-sign-out"></i> Log Out</a></li>
                           </ul>
                        </li>
                        <!-- <li> <a data-toggle="ui-aside-right" href=""><i class="glyphicon glyphicon-option-vertical"></i></a> </li> -->
                     </ul>
                     <!--notification end-->
                  </div>
               </div>
            </div>
         </div>
      </header>
      <!--header end-->
      <!--nav start-->
<nav class="navbar navbar-inverse yamm navbar-default hori-nav uis-header--green" role="navigation">
  <div class="container">
    <div class="row">
      <div class="navbar-header" style="float:left;width:auto;">
        <!--toggle bar for responsive start-->
        <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#main-navigations" style="float:left;margin-left:0;margin-right:0;">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <!--toggle bar for responsive end-->
        <a href="<?=base_index();?>" class="navbar-brand visible-xs-inline-block" style="margin-left:10px;float:left;">
          <img src="<?=getPengaturan('logo'); ?>" alt="" style="width:32px;display:inline-block;vertical-align:middle;"> <span style="vertical-align:middle;">PMB</span>
        </a>
      </div>
      <div class="collapse navbar-collapse" id="main-navigations">
        <ul class="nav navbar-nav">
          <li class="<?=(uri_segment(0)=='')?'active':'';?>">
            <a href="<?=base_index();?>">
              <i class="fa fa-dashboard"></i> <span> Dashboard</span></a>
          </li>
          <?php echo createMenu('horizontal'); ?>
          <?php if (isset($_SESSION['login_as'])) { ?>
            <li>
              <a href="<?=base_admin();?>inc/login_back.php?id=<?=$_SESSION['admin_id']; ?>">
                <i class="fa fa-user"></i> <span>Login Back</span>
              </a>
            </li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </div>
</nav>


<link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/dist/css/horizontal_nav.css">
<link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/dist/css/menu/bootstrap-submenu.css">
<script type="text/javascript" src="<?=base_admin();?>assets/dist/css/menu/bootstrap-submenu.js"></script>
<script type="text/javascript" src="<?=base_admin();?>assets/dist/css/menu/bootstrap-hover-dropdown.js"></script>
<script type="text/javascript">
  $(document).ready(function () {
    // yamm mega menu
    $(document).on('click', '.yamm .dropdown-menu', function(e) {
      e.stopPropagation()
    });
    //bootstrap sub menu
    $('[data-submenu]').submenupicker();
  });
</script>
<style type="text/css">
.top-head .navbar-brand {
  display: flex;
  align-items: center;
}
</style>