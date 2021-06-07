<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostDao.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();
$activeMenu = 'profile';
$user = [];
$feed = [];

$id = filter_input(INPUT_GET, 'id');
if(!$id) $id = $userInfo->id;

if($id == $userInfo->id) {
    $activeMenu = '';
}

$postDao = new PostDao($pdo);
$userDao = new UserDao($pdo);

// pegando informações do usuário
$user = $userDao->findById($id, true);

if(!$user) {
    header("Location: ".$base); 
    exit;
}

$dateFrom = new DateTime($user->birthdate);
$dateTo = new DateTime('today');
$user->ageYears = $dateFrom->diff($dateTo)->y;

// pegando o feed do usuário
$feed = $postDao->getUserFeed($id);

require_once 'partials/header.php';
//require_once 'partials/menu.php';
?>

<section class="container main">
    <aside class="mt-10">
        <nav>
            <a href="<?= $base; ?>">
                <div class="menu-item">
                    <div class="menu-item-icon">
                        <img src="assets/images/home-run.png" width="16" height="16" />
                    </div>
                    <div class="menu-item-text">
                        Home
                    </div>
                </div>
            </a>
            <a href="">
                <div class="menu-item active">
                    <div class="menu-item-icon">
                        <img src="assets/images/user.png" width="16" height="16" />
                    </div>
                    <div class="menu-item-text">
                        Meu Perfil
                    </div>
                </div>
            </a>
            <a href="">
                <div class="menu-item">
                    <div class="menu-item-icon">
                        <img src="assets/images/friends.png" width="16" height="16" />
                    </div>
                    <div class="menu-item-text">
                        Amigos
                    </div>
                    <div class="menu-item-badge">
                        33
                    </div>
                </div>
            </a>
            <a href="">
                <div class="menu-item">
                    <div class="menu-item-icon">
                        <img src="assets/images/photo.png" width="16" height="16" />
                    </div>
                    <div class="menu-item-text">
                        Fotos
                    </div>
                </div>
            </a>
            <div class="menu-splitter"></div>
            <a href="">
                <div class="menu-item">
                    <div class="menu-item-icon">
                        <img src="assets/images/settings.png" width="16" height="16" />
                    </div>
                    <div class="menu-item-text">
                        Configurações
                    </div>
                </div>
            </a>
            <a href="">
                <div class="menu-item">
                    <div class="menu-item-icon">
                        <img src="assets/images/power.png" width="16" height="16" />
                    </div>
                    <div class="menu-item-text">
                        Sair
                    </div>
                </div>
            </a>
        </nav>
    </aside>
    <section class="feed">

        <div class="row">
            <div class="box flex-1 border-top-flat">
                <div class="box-body">
                    <div class="profile-cover" style="background-image: url('<?=$base?>/media/covers/<?=$user->cover;?>');"></div>
                    <div class="profile-info m-20 row">
                        <div class="profile-info-avatar">
                            <img src="<?=$base;?>/media/avatars/<?=$user->avatar;?>" />
                        </div>
                        <div class="profile-info-name">
                            <div class="profile-info-name-text"><?=$user->name;?></div>
                            <?php if(!empty($user->city)): ?>
                                <div class="profile-info-location"><?=$user->city;?></div>
                            <?php endif; ?>
                        </div>
                        <div class="profile-info-data row">
                            <div class="profile-info-item m-width-20">
                                <div class="profile-info-item-n"><?=count($user->followers);?></div>
                                <div class="profile-info-item-s">Seguidores</div>
                            </div>
                            <div class="profile-info-item m-width-20">
                                <div class="profile-info-item-n"><?=count($user->following);?></div>
                                <div class="profile-info-item-s">Seguindo</div>
                            </div>
                            <div class="profile-info-item m-width-20">
                                <div class="profile-info-item-n"><?=count($user->photos);?></div>
                                <div class="profile-info-item-s">Fotos</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="column side pr-5">

                <div class="box">
                    <div class="box-body">

                        <div class="user-info-mini">
                            <img src="<?=$base;?>/assets/images/calendar.png" />
                            <?=date('d/m/y', strtotime($user->birthdate));?> (<?=$user->ageYears;?> anos)
                        </div>
                        <?php if(!empty($user->city)): ?>
                            <div class="user-info-mini">
                                <img src="<?=$base;?>/assets/images/pin.png" />
                                <?=$user->city;?>
                            </div>
                        <?php endif; ?>

                        <?php if(!empty($user->work)): ?>
                            <div class="user-info-mini">
                                <img src="<?=$base;?>/assets/images/work.png" />
                                <?=$user->work;?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="box">
                    <div class="box-header m-10">
                        <div class="box-header-text">
                            Seguindo
                            <span>(<?=count($user->following);?>)</span>
                        </div>
                        <div class="box-header-buttons">
                            <a href="<?=$base;?>/friends.php?id=<?=$user->id;?>">ver todos</a>
                        </div>
                    </div>
                    <div class="box-body friend-list">
                        <?php if(count($user->following) > 0): ?>
                            <?php foreach ($user->following as $item): ?>
                                <div class="friend-icon">
                                    <a href="<?=$base;?>/profile.php?id=<?=$item->id;?>">
                                        <div class="friend-icon-avatar">
                                            <img src="<?=$base;?>/media/avatars/<?=$item->avatar;?>" />
                                        </div>
                                        <div class="friend-icon-name">
                                            <?=$item->name;?>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
            <div class="column pl-5">

                <div class="box">
                    <div class="box-header m-10">
                        <div class="box-header-text">
                            Fotos
                            <span>(<?=count($user->photos);?>)</span>
                        </div>
                        <div class="box-header-buttons">
                            <a href="<?=$base;?>/photos.php?id=<?=$user->id;?>">ver todos</a>
                        </div>
                    </div>
                    <div class="box-body row m-20">
                        <?php if(count($user->photos) > 0): ?>
                            <?php foreach($user->photos as $item): ?>
                               <div class="user-photo-item">
                                <a href="#modal-1" rel="modal:open">
                                    <img src="<?=$base;?>/media/uploads/<?=$item->body;?>" />
                                </a>
                                <div id="modal-1" style="display:none">
                                    <img src="<?=$base;?>/media/uploads/<?=$item->body;?>" />
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <?php if($id == $userInfo->id): ?>
                <?php require_once 'partials/feed-editor.php'; ?>
            <?php endif; ?>

            <?php if(count($feed) > 0): ?>
                <?php foreach($feed as $item): ?>
                    <?php require_once 'partials/feed-item.php'; ?>
                <?php endforeach; ?>
            <?php else: ?>
                Não há postagens.
            <?php endif; ?>


        </div>

    </div>

</section>
</section>

<?php require_once 'partials/footer.php'; ?>