# DB設計書

## ER図（テーブル同士の関係）

- tags（1）─（多）tasks：1つのタグは複数のタスクに使われる
- statuses（1）─（多）tasks：1つの状態は複数のタスクに使われる

\`\`\`
tags ──1───多── tasks ──多───1── statuses
\`\`\`

## テーブル定義

### tasks（タスク）

| 項目名 | 型 | 制約 | 説明 |
|---|---|---|---|
| task_id | INT | PRIMARY KEY | 自動で振られる番号 |
| title | VARCHAR | NOT NULL | タスクの名前 |
| due_date | DATETIME | NOT NULL | 締め切り日時 |
| estimated_minutes | INT | | 所要時間（分） |
| tag_id | INT | NOT NULL | タグ（tagsと紐づく） |
| status_id | INT | | 状態（statusesと紐づく） |
| progress | INT | | 進捗率（0〜100%） |

### tags（タグ）

| 項目名 | 型 | 制約 | 説明 |
|---|---|---|---|
| tag_id | INT | PRIMARY KEY | 自動で振られる番号 |
| name | VARCHAR | NOT NULL | タグ名 |

### statuses（状態）

| 項目名 | 型 | 制約 | 説明 |
|---|---|---|---|
| status_id | INT | PRIMARY KEY | 自動で振られる番号 |
| name | VARCHAR | NOT NULL | 状態名 |

# DB設計書

## ER図（テーブル同士の関係）

- tags（1）─（多）tasks：1つのタグは複数のタスクに使われる
- statuses（1）─（多）tasks：1つの状態は複数のタスクに使われる

\`\`\`
tags ──1───多── tasks ──多───1── statuses
\`\`\`

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

### tags（タグ）

| 項目名 | 型 | 制約 | 説明 |
|---|---|---|---|
| tag_id | INT | PRIMARY KEY | 自動で振られる番号 |
| name | VARCHAR | NOT NULL | タグ名 |

### statuses（状態）

| 項目名 | 型 | 制約 | 説明 |
|---|---|---|---|
| status_id | INT | PRIMARY KEY | 自動で振られる番号 |
| name | VARCHAR | NOT NULL | 状態名 |