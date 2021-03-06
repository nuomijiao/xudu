<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/9
 * Time: 13:37
 */

namespace app\xdapi\controller\v1;


use app\lib\exception\MomentsException;
use app\lib\exception\ParameterException;
use app\xdapi\controller\BaseController;
use app\xdapi\model\WhFriends;
use app\xdapi\model\WhMoments;
use app\xdapi\model\WhTempImgs;
use app\xdapi\model\WhUser;
use app\xdapi\service\Moment as MomentService;
use app\xdapi\service\Picture;
use app\xdapi\service\Token;
use app\xdapi\validate\CommentNew;
use app\xdapi\validate\IDMustBePositiveInt;
use app\xdapi\validate\MomentNew;
use app\xdapi\validate\PagingParameter;

class Moment extends BaseController
{

    //动态图片上传
    public function addMomentImg()
    {
        $uid = Token::getCurrentUid();
        $moment_img = $this->request->file('moment_img');
        $data = Picture::uploadImg($moment_img, 'moment_tmp_img');
        //存到临时图片文件夹
        $img = WhTempImgs::create([
            'img_url' => $data['url'],
            'img_name' => $data['filename'],
            'user_id' => $uid,
        ]);
        return $this->xdreturn($img);
    }


    //发布动态
    public function addMoment()
    {
        $request = (new MomentNew())->goCheck();

        $title = $request->param('title');
        $ids  = $request->param('ids');
        $location = $request->param('location');

        $uid = Token::getCurrentUid();
        //上传动态
        $data = MomentService::releaseMoment($uid,$title,$location, $ids);
        return $this->xdreturn($data);
    }

    //获取热门动态，没有评论
    public function getHot($page = 1, $size = 10)
    {
        (new PagingParameter())->goCheck();
        $uid = Token::getCurrentUid();
        $pagingMoments = WhMoments::getHotMoments($uid, $page, $size);
        if ($pagingMoments->isEmpty()) {
            throw new MomentsException([
                'msg' => '热门动态已见底线',
                'errorCode' => 70001,
            ]);
        }
        $data = $pagingMoments->toArray();
        return json([
            'error_code' => 'Success',
            'data' => $data,
            'current_page' => $pagingMoments->getCurrentPage(),
        ]);
    }


    //获取关注好友动态，没有评论
    public function getFollow($page = 1, $size = 10)
    {
        (new PagingParameter())->goCheck();
        $uid = Token::getCurrentUid();
        //获取好友id列表
        $friends = WhFriends::getFriendListId($uid);
        $friends_ids = '';
        foreach($friends as $key => $value) {
            $friends_ids .= $value->friend_id.",";
        }
        $friends_ids = rtrim($friends_ids);
        $pagingMoments = WhMoments::getFollowMoments($uid, $friends_ids, $page, $size);
        if ($pagingMoments->isEmpty()) {
            throw new MomentsException([
                'msg' => '关注动态已见底线',
                'errorCode' => 70002,
            ]);
        }
        $data = $pagingMoments->toArray();
        return json([
            'error_code' => 'Success',
            'data' => $data,
            'current_page' => $pagingMoments->getCurrentPage(),
        ]);
    }

    public function myMomment($page = 1, $size = 10)
    {
        (new PagingParameter())->goCheck();
        $uid = Token::getCurrentUid();
        $pagingMoments = WhMoments::getMyMoments($uid, $page, $size);
        if ($pagingMoments->isEmpty()) {
            throw new MomentsException([
                'msg' => '我的动态已见底线',
                'errorCode' => 70007,
            ]);
        }
        $data = $pagingMoments->toArray();
        return json([
            'error_code' => 'Success',
            'data' => $data,
            'current_page' => $pagingMoments->getCurrentPage(),
        ]);
    }

    public function userMoment($id = '', $page = 1, $size = 10)
    {
        (new IDMustBePositiveInt())->goCheck();
        (new PagingParameter())->goCheck();
        $uid = Token::getCurrentUid();
        $pagingMoments = WhMoments::getUserMoments($uid, $id, $page, $size);
        if ($pagingMoments->isEmpty()) {
            throw new MomentsException([
                'msg' => '用户动态已见底线',
                'errorCode' => 70010,
            ]);
        }
        $data = $pagingMoments->toArray();
        return json([
            'error_code' => 'Success',
            'data' => $data,
            'current_page' => $pagingMoments->getCurrentPage(),
        ]);
    }

    //动态点赞
    public function clickZan($id = '')
    {
        (new IDMustBePositiveInt())->goCheck();
        $uid = Token::getCurrentUid();
        $isZan = MomentService::dealZan($id, $uid);
        return $this->xdreturn($isZan);
    }


    //获取动态详情,包括评论
    public function getCommentDetail($id = '')
    {
        (new IDMustBePositiveInt())->goCheck();
        $uid = Token::getCurrentUid();
        $commentDetail = WhMoments::getDetail($id, $uid);
        if (!$commentDetail) {
            throw new MomentsException();
        }
        //检查是否是好友
        $friends  = WhFriends::checkIsFriends($commentDetail->user_id, $uid);
        if ($friends) {
            $commentDetail->is_friends = 1;
        } else {
            $commentDetail->is_friends = 0;
        }
        $comments = MomentService::getComments($id);
        $commentDetail->comments = $comments;
        return $this->xdreturn($commentDetail);
    }


    //评论动态 $id 动态的id
    public function comment($id = '', $content = '')
    {
        (new CommentNew())->goCheck();
        $uid = Token::getCurrentUid();
        //判断操作是否合法，自己的动态自己不能评论，非好友动态不能评论
        $moment = MomentService::checkOperateComment($id, $uid);
        $comment = MomentService::addComment($uid, $moment->id, $content, $moment->user_id);
        return $this->xdreturn($comment);
    }

    //作者回复评论,$id 评论的id
    public function replyComment($id = '', $content = '')
    {
        (new CommentNew())->goCheck();
        $uid = Token::getCurrentUid();
        //判断操作是否合法，不能回复给别的作者的评论
        $comment = MomentService::checkOperateReply($id, $uid);
        $replycomment = MomentService::addComment($uid, $comment->moment_id, $content, $comment->user_id, $id);
        return $this->xdreturn($replycomment);
    }

    public function ownerInfo($id = '')
    {
        (new IDMustBePositiveInt())->goCheck();
        $uid = Token::getCurrentUid();
        if ($uid == $id) {
            throw new MomentsException([
                'msg' => '自己的动态',
                'errorCode' => 70008,
            ]);
        }
        $userInfo = WhUser::get($id);
        $is_friends = WhFriends::checkIsFriends($uid, $id);
        if ($is_friends) {
            $userInfo->is_guanzhu = 1;
        } else {
            $userInfo->is_guanzhu = 0;
        }
        $userInfo->pics = MomentService::getImgs($id);
        return $this->xdreturn($userInfo);
    }

}