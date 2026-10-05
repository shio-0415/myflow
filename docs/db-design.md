# DB設計書

## ER図（テーブル同士の関係）

- tags（1）─（多）tasks：1つのタグは複数のタスクに使われる
- statuses（1）─（多）tasks：1つの状態は複数のタスクに使われる

```
tags ──1───多── tasks ──多───1── statuses
```

## テーブル定義

### tasks（タスク）

| 項目名 | 型 | 制約 | 説明 |
|---|---|---|---|
| task_id | INT | PRIMARY KEY | 自動で振られる番号 |
| title | VARCHAR | NOT NULL | タスクの名前 |
| due_date | DATETIME | NOT NULL | 締め切り日時 |
| estimated_minutes | INT | | 所要時間（分） |
| tag_id | INT | NOT NULL | タグ（tagsと紐づく） |
| status_id | INT | DEFAULT 1 | 状態（statusesと紐づく）。未入力の場合は1（未着手）になる |
| progress | INT | | 進捗率（0〜100%） |
| created_at | DATETIME | NOT NULL | タスクを作成した日時 |
| completed_at | DATETIME | | タスクを完了した日時。未完了の間は空。状態を完了（3）にしたときに日時を入れる |

### tags（タグ）

| 項目名 | 型 | 制約 | 説明 |
|---|---|---|---|
| tag_id | INT | PRIMARY KEY | 自動で振られる番号 |
| name | VARCHAR | NOT NULL | タグ名 |

### statuses（状態）

| 項目名 | 型 | 制約 | 説明 |
|---|---|---|---|
| status_id | INT | PRIMARY KEY | 自動で振られる番号 |
| name | VARCHAR | NOT NULL | 状態名（1＝未着手、2＝進行中、3＝完了） |

## アプリを開いた日の記録（KPI②用）

MVPはログインがなく、データの保存先も`localStorage`（ブラウザの中）のため、DBのテーブルは作らず、`localStorage`に日付の一覧として残す。

- 保存するもの：アプリを開いた日付の一覧（例：2025-10-12、2025-10-13）
- 記録のしかた：アプリを開いたとき、その日の日付が一覧になければ1つ足す。同じ日に何回開いても、1日分として数える
- 数え方：1週間（月曜〜日曜）の中にある日付の数を数える。5個以上ならKPI②は達成

## KPIとの対応

| KPI | 測り方 | 使うデータ |
|---|---|---|
| ① 1週間に設定したタスクのうち、80%以上を完了させる | その週に作ったタスクのうち、完了したものの割合を出す | created_at、status_id |
| ② 1週間のうち、5日以上アプリを開く | 1週間の中にある、開いた日付の数を数える | localStorageの日付の一覧 |
| ③ 締め切りに間に合わなかった回数を、1か月に4回以下に抑える | 完了日時が締め切りより後のタスクの数を数える | completed_at、due_date |