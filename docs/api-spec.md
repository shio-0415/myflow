# API仕様書

## エンドポイント一覧

| No | メソッド | エンドポイント | 内容 |
|---|---|---|---|
| 1 | GET | /tasks | タスク一覧を取得する |
| 2 | POST | /tasks | 新しいタスクを作成する |
| 3 | PUT | /tasks/{タスクID} | タスクを更新する |
| 4 | DELETE | /tasks/{タスクID} | タスクを削除する |
| 5 | GET | /tags | タグ一覧を取得する |
| 6 | POST | /tags | 新しいタグを作成する |
| 7 | GET | /statuses | 状態一覧を取得する |
| 8 | GET | /tasks/{タスクID} | タスクを1件だけ取得する |
| 9 | PUT | /tags/{タグID} | タグを更新する |
| 10 | DELETE | /tags/{タグID} | タグを削除する |

## リクエスト/レスポンス例

### POST /tasks（タスクを作成する）

リクエスト:
```json
{
  "title": "ES提出",
  "tag_id": 1,
  "status_id": 1,
  "due_date": "2025-12-01",
  "estimated_minutes": 60
}
```

レスポンス:
```json
{
  "task_id": 15,
  "title": "ES提出",
  "tag_id": 1,
  "status_id": 1,
  "due_date": "2025-12-01",
  "estimated_minutes": 60,
  "progress": 0,
  "created_at": "2025-11-20 09:00",
  "completed_at": null
}
```

※ created_at（作成日時）は、タスクを作ったときに自動で入る。completed_at（完了日時）は、完了するまで null（空）になる。

### GET /tasks（タスク一覧を取得する）

リクエスト:
`GET /tasks`（JSONのボディは不要）

クエリパラメータ（任意）:
- `tag_id`：指定したタグのタスクのみ取得する（例：`?tag_id=1`）
- `sort`：指定した項目で並び替える（例：`?sort=due_date`）
- 組み合わせ例：`GET /tasks?tag_id=1&sort=due_date`

レスポンス:
```json
[
  {
    "task_id": 15,
    "title": "ES提出",
    "tag_id": 1,
    "status_id": 1,
    "due_date": "2025-12-01",
    "estimated_minutes": 60,
    "progress": 0,
    "created_at": "2025-11-20 09:00",
    "completed_at": null
  },
  {
    "task_id": 16,
    "title": "レポート提出",
    "tag_id": 2,
    "status_id": 2,
    "due_date": "2025-12-05",
    "estimated_minutes": 120,
    "progress": 50,
    "created_at": "2025-11-21 10:30",
    "completed_at": null
  },
  {
    "task_id": 17,
    "title": "企業説明会の予約",
    "tag_id": 1,
    "status_id": 3,
    "due_date": "2025-11-25",
    "estimated_minutes": 15,
    "progress": 100,
    "created_at": "2025-11-20 09:30",
    "completed_at": "2025-11-22 18:00"
  }
]
```

### PUT /tasks/{タスクID}（タスクを更新する：完了にする例）

状態を完了（status_idが3）に変えたとき、completed_at に、そのときの日時が自動で入る。

リクエスト（PUT /tasks/16）:
```json
{
  "status_id": 3,
  "progress": 100
}
```

レスポンス:
```json
{
  "task_id": 16,
  "title": "レポート提出",
  "tag_id": 2,
  "status_id": 3,
  "due_date": "2025-12-05",
  "estimated_minutes": 120,
  "progress": 100,
  "created_at": "2025-11-21 10:30",
  "completed_at": "2025-12-04 20:00"
}
```

## バリデーションルール

| 項目 | ルール |
|---|---|
| title（タイトル） | 必須。空欄不可 |
| due_date（期限） | 必須。空欄不可 |
| tag_id（タグ） | 必須。空欄不可 |
| status_id（状態） | 任意。未入力の場合は自動で「未着手」にする |
| estimated_minutes（所要時間） | 任意 |
| progress（進捗率） | 0〜100の範囲内であること |

## エラーレスポンス

| 状況 | ステータスコード | レスポンス例 |
|---|---|---|
| 入力内容が不正（例：タイトルが空欄） | 400 | `{ "error": "タイトルは必須です" }` |
| 指定したデータが存在しない（例：タスクIDが見つからない） | 404 | `{ "error": "指定されたタスクが見つかりません" }` |