<style>
body{background:#f7f7f7;}
.story{margin:20px auto;max-width:700px;}
.story hr{cursor:default;margin:20px 0px;border:1px solid #ddd;}
.story ul{list-style:none inside;padding:0px 10px;}
.story .bar-heading{margin:10px 0px 10px;}
.story .pboard{background:#fff;padding:10px;border-radius:4px;border:1px solid #eee;}
.story .pboard div>div{font-size:12px;}
.story .pboard h1{margin:0px;}
.story .pboard h2{margin:0px;font-size:16px;color:#999;font-weight:normal;}
.story .pboard .pull-right{padding-left:20px;border-left:1px solid #eee;}
.story .pboard .pull-right img{width:40px;margin:0px 0px -1px 8px;float:right;border-radius:3px;}
.story .pboard .glyphicon{color:#999;font-size:7px;border:1px solid #ddd;padding:2px;border-radius:2px;vertical-align:middle;margin-top:-4px;}

.story .pcard{margin:10px;padding-bottom:10px;border-bottom:1px solid #f0f0f0;}
.story .pcard .-avatar{float:left;}
.story .pcard .-avatar img{width:40px;height:40px;border-radius:4px;}
.story .pcard .-poster{margin-left:50px;}
.story .pcard .-time{margin-left:50px;font-size:12px;color:#ccc;}

.story .post{border:1px solid #eee;background:#fff;margin:10px 0px 10px;border-radius:4px;}
.story .post .-title{margin:0px;padding:0px 10px;}
.story .post .-title a{color:#52BBC3}
.story .post .-detail{margin:10px;}
.story .post .-detail img{max-width:100%;height:auto;}
</style>

<div class="story">
  <div class="pboard clearfix">
    <div class="pull-left">
      <h1><a href="/<?php echo $this->blog['l']?>"><?php echo $this->blog['t']?></a></h1>
      <h2><?php echo $this->blog['d']?></h2>
    </div>
    <div class="pull-right">
      <div><span class="glyphicon glyphicon-list"></span> <?php echo $this->cate[$this->blog['c']]['t']?></div>
    </div>
  </div>
  <div class="posts">
    <?php for ($i = 0; $i < count($this->post); $i++):$u=$this->user->get($this->post[$i]['u'])?>
    <div class="post">
      <div class="pcard clearfix">
        <div class="pull-left">
          <div class="-avatar"><a href="<?php echo $u['link']?>"><img src="<?php echo $u['img']?>"></a></div>
          <div class="-poster"><a href="<?php echo $u['link']?>"><?php echo $u['name']?></a></div>
          <div class="-time"><?php echo self::Time()->from($this->post[$i]['ds'],'date')?></div>
        </div>
        <div class="pull-right">

        </div>
      </div>
      <h3 class="-title"><a href="/<?php echo $this->post[$i]['bl'].'/'.$this->post[$i]['_id'].'/'.$this->post[$i]['l']?>"><?php echo $this->post[$i]['t']?></a></h3>
      <div class="-detail"><?php echo $this->post[$i]['d']?></div>
    </div>
    <?php endfor?>
  </div>
</div>