<?php
namespace Jarm\App\Control;
use Jarm\Core\Load;

class News extends Service
{
  public $news=[];
  public function _news()
  {
    $path=(Load::$path[1]?:'home');
    if(is_numeric($path))
    {
      return (new \Jarm\App\Control\News_Update())->get($this,$path);
    }
    elseif(in_array($path,['upload','report','topnews','stats']))
    {
      $c='\Jarm\App\Control\News_'.ucfirst($path);
      return (new $c())->get($this,Load::$path[2]);
    }
    else
    {
      return (new \Jarm\App\Control\News_Home())->get($this,$path);
    }
  }

  public function instant($i)
  {
    $db=Load::DB();
    if($this->news=$db->findone('news',['_id'=>intval($i),'dd'=>['$exists'=>false]]))
    {
      if(Load::$my['am'])
      {
        $db->update('news',['_id'=>intval($i)],['$set'=>['di'=>Load::Time()->now()]]);
        Load::Http()->get(Load::uri(['cache','/clear/'.Load::getServ($this->news['sv']).'/'.$this->news['fd']]));
        Load::Ajax()->alert('จัดคิวสำหรับข่าวนี้เรียบร้อยแล้ว');
      }
      else
      {
        Load::Ajax()->alert('คุณไม่มีสิทธ์ลบข่าวนี้');
      }
    }
  }

  public function delnews($i)
  {
    $db=Load::DB();
    if($this->news=$db->findone('news',['_id'=>intval($i),'dd'=>['$exists'=>false]]))
    {
      $this->news=(new \Jarm\App\News\Service(['ignore'=>1]))->fetch($this->news);
      if(Load::Time()->sec($this->news['da'])<time()-(3600*24*EXPIRE_NEWS))
      {
        Load::Ajax()->alert('ข่าวนี้หมดอายุสำหรับการลบหรือแก้ไขแล้ว');
        return;
      }
      if(Load::$my['am'])
      {
        $db->update('news',['_id'=>intval($i)],['$set'=>['dd'=>Load::Time()->now()]]);
        list($scheme,$key)=explode('://',$this->news['link']);
        Load::$core->delete($key)
                  ->delete('news.'.Load::$conf['domain'].'/home');
        Load::Ajax()->redirect(URL);
      }
      else
      {
        Load::Ajax()->alert('คุณไม่มีสิทธ์ลบข่าวนี้');
      }
    }
    else
    {
      Load::Ajax()->redirect(URL);
    }
  }

  public function newnews($arg)
  {
    $ajax=Load::Ajax();
    $db=Load::DB();
    if(!$arg['title'])
    {
      $ajax->alert('กรุณากรอกชื่อข่าว');
    }
    elseif(!$arg['type'])
    {
      $ajax->alert('กรุณาเลือกประเภทข่าว');
    }
    else
    {
      $_=[
        't'=>mb_substr(trim($arg['title']),0,100,'utf-8'),
        'u'=>Load::$my['_id'],
        'pl'=>0,
      ];

      $_cs=explode('-',trim($arg['type']));
      $_['c']=intval($_cs[0]);
      $_['cs']=intval($_cs[1]);
      $_['cs2']=intval($_cs[2]);

      $ksv=[];
      foreach(Load::$conf['server']['files'] as $k=>$v)
      {
        if($v['upload'])
        {
          $ksv[]=$k;
        }
      }
      if(count($ksv)==0)
      {
        $ajax->alert('ไม่มี server รองรับการ upload รูปภาพ');
      }
      elseif($id=$db->insert('news',$_))
      {
        $sv=$ksv[$id%count($ksv)];
        $db->update('news',['_id'=>$id],['$set'=>['sv'=>$sv,'fd'=>date('Y/m/').$id]]);
        $ajax->redirect('/news/'.$id);
      }
      else
      {
        $ajax->alert('เกิดข้อผิดพลาด ไม่สามารถเพิ่มข้อมูลได้ในขณะนี้');
      }
    }
  }

  public function addview($do)
  {
    $db=Load::DB();
    $ajax=Load::Ajax();
    if(Load::$my&&Load::$my['am']>=9)
    {
      $inc=intval($do);
      if($inc!=0)
      {
        $db->update('news',['_id'=>$this->news['_id']],['$inc'=>['do'=>$inc]]);
        $db->insert('logs',['ty'=>'addview','u'=>Load::$my['_id'],'news'=>$this->news['_id'],'do'=>$inc]);
      }
      Load::move(URL.'?r='.time());
    }
    else
    {
      $ajax->alert('ไม่สามารถใช้งานส่วนนี้ได้');
    }
  }

  public function delattach($a)
  {
    if($a)
    {
      Load::Upload()->post($this->news['sv'],'delete','news/'.$this->news['fd'].'/'.$a);
    }
    Load::Ajax()->redirect(URL);
  }
}
?>
