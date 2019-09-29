<?php

namespace App\Models;

class Topic extends Model
{
    protected $fillable = ['title', 'body', 'user_id', 'category_id', 'reply_count', 'view_count', 'last_reply_user_id', 'order', 'excerpt', 'slug'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeWithOrder($query, $order)
    {
        //不同的排序，使用不用的数据读取逻辑
        switch ($order) {
            case 'recent':
                $query->recnet();
                break;
            default:
                $query->recentReplied();
                break;
        }
        //预加载防止N+1
        return $query->with('user', 'category');
    }

    public function scopeRecentReplied($query)
    {
        //当话题有新回复时，我们将编写逻辑来更新话题模型reply_count 属性
        //此时会自动出发框架对数据模型updated_at 时间戳的更新
        return $query->orderBy('orderby','desc');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at','desc');
    }


}
