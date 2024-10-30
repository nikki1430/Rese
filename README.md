# 
***
## アプリケーション概要
ログインしたユーザーが出勤時間・退勤時間・休憩開始時間・休憩終了時間の登録ができる。
また、管理画面から、日付別、ユーザー別の出退勤の記録の参照ができる

## アプリケーションURL
ec2-35-78-71-94.ap-northeast-1.compute.amazonaws.com

## 機能一覧
* ログイン機能
* メール認証機能
* 出退勤・休憩入・戻時間の打刻
* 日付別の勤怠状況の参照
* ユーザー別の勤怠状況の参照
* ユーザー一覧の参照

## 使用技術
* Laravel8
* php8.2
* mysql:8.0.32
* Fortify(ユーザー認証・メール認証）

## テーブル設計
<img width="475" alt="テーブル仕様書" src="https://github.com/nikki1430/atte/assets/164667475/ba7023ff-6919-4475-b3ea-37faea674b9c">

## ER図
# atte
***
## アプリケーション概要
ログインしたユーザーが出勤時間・退勤時間・休憩開始時間・休憩終了時間の登録ができる。
また、管理画面から、日付別、ユーザー別の出退勤の記録の参照ができる

## アプリケーションURL
ec2-35-78-71-94.ap-northeast-1.compute.amazonaws.com

## 機能一覧
* ログイン機能
* メール認証機能
* 出退勤・休憩入・戻時間の打刻
* 日付別の勤怠状況の参照
* ユーザー別の勤怠状況の参照
* ユーザー一覧の参照

## 使用技術
* Laravel8
* php8.2
* mysql:8.0.32
* Fortify(ユーザー認証・メール認証）

## テーブル設計
<img width="475" alt="テーブル仕様書" src="https://github.com/nikki1430/atte/assets/164667475/ba7023ff-6919-4475-b3ea-37faea674b9c">

## ER図
<img width="724" alt="スクリーンショット 2024-07-16 12 39 17" src="https://github.com/user-attachments/assets/d0441448-ae55-48cd-8ae9-66a09d6bb460">

***
## 環境構築

### セットアアップ
<img width="192" alt="スクリーンショット 2024-07-16 12 37 51" src="https://github.com/user-attachments/assets/405fb449-de97-4810-b2b9-3389a6ed760c">

* 上記ディレクトリを作成
* 各ファイルの記述を終えたら
  docker-compose up -d --build　でビルド
***
### laravelインストール
* docker-compose exec php bash　(phpコンテナ内にログイン)
* composer -v (composerインストール)
* composer create-project "laravel/laravel=8.*" . --prefer-dist (laravelプロジェクト作成)
***
### ec2デプロイ
#### ec2インスタンス作成
* AWSからログインしてEC2画面にアクセス
* 「インスタンスを起動」をクリック
* マシンイメージで「Linux」を指定
* キーペアの作成
* インスタンスタイプで「t2.micro」を選択
* ネットワーク設定で「HTTP(80)」と「HTTPS(443)」を追加
* 「インスタンスを起動」をクリック
* mv Downloads/sample-key.pem .ssh/ (「キー.pem」を.sshディレクトリに移動)
* cd .ssh/
* chmod 400 sample-key.pem
* ssh -i "sample-key.pem" ec2-user@ec2-??-??-???-???.ap-northeast-1.compute.amazonaws.com（AWS 接続画面記載のコードをコピペ）
***
#### nginxをセットアップ
* sudo amazon-linux-extras enable nginx1
* sudo yum -y install nginx #インストール
* sudo nginx -v #バージョン確認
* sudo systemctl enable nginx #nginx自動起動化
* sudo systemctl start nginx.service #起動
* sudo systemctl status nginx.service #起動確認
***
#### RDSを作成
* RDS画面にアクセス
* データベースページに移動し、データベースの作成をクリック
* エンジンでMySQLを選択
* テンプレートを無料利用枠に指定
* VPCセキュリティグループを作成し任意のセキュリティグループ名とec2インスタンスと同じアベイラビリティゾーンを指定
* 追加設定でデータベース名を入力する
* 一番下の「データベースの作成」ボタンをクリック
* 作成したDB（database-1)をクリック
* VPCセキュリティグループをクリック
* 「セキュリティグループ」をクリック→「インバウンドのルールを編集」をクリック
* EC2インスタンス作成時に設定したセキュリティグループを選択
***
#### 接続
* ターミナルからインスタンスに接続
* sudo yum update -y (パッケージのアップデート)
* sudo yum -y install mysql git httpd curl
* mysql -h database-1.cveawoxgeidk.ap-northeast-3.rds.amazonaws.com -P 3306 -u admin -p（パスワードはDBを作成するときに自分で入力したマスターパスワード）
***
#### 環境作成
* amazon-linux-extras list （利用できるパッケージの確認）
* sudo amazon-linux-extras install -y php8.2
* https://getcomposer.org/download/　（公式サイトに従ってComposer をインストールする）
* cd /var/www
* sudo chown ec2-user:ec2-user /var/www　（/var/wwwディレクトリの所有者とグループを変更）
* git clone https://github.com/???????.git　（デプロイしたいLaravelのプロジェクトをclone）
* sudo yum install -y php-xml　（Composerのパッケージをインストールするために必要なライブラリをインストール）
* cd アプリディレクトリ
* composer update
* composer install
* sudo yum install php php-devel php-opcache php-mbstring php-xml
* sudo systemctl start php-fpm.service
* sudo systemctl enable php-fpm.service
* systemctl status php-fpm.service
* https://knmts.com/become-engineer-30/ (記事を参考にphp-fpmとnginxの接続設定をする)
* クローンしたLaravelのプロジェクトディレクトリ内に移動して.envファイルの作成とAPP_KEYの生成を行う
* cd var/www/Laravelプロジェクト
* cp .env.example .env
* php artisan key:generate
* envファイルを設定して、RDSとアプリを繋げる
* php artisan migrate:fresh　--seed (migrateしてDBを作成する。)
* sudo chmod -R 777 storage  (app/storageの権限変更)







***
## 環境構築

### セットアアップ
<img width="192" alt="スクリーンショット 2024-07-16 12 37 51" src="https://github.com/user-attachments/assets/405fb449-de97-4810-b2b9-3389a6ed760c">

* 上記ディレクトリを作成
* 各ファイルの記述を終えたら
  docker-compose up -d --build　でビルド
***
### laravelインストール
* docker-compose exec php bash　(phpコンテナ内にログイン)
* composer -v (composerインストール)
* composer create-project "laravel/laravel=8.*" . --prefer-dist (laravelプロジェクト作成)
***
### ec2デプロイ
#### ec2インスタンス作成
* AWSからログインしてEC2画面にアクセス
* 「インスタンスを起動」をクリック
* マシンイメージで「Linux」を指定
* キーペアの作成
* インスタンスタイプで「t2.micro」を選択
* ネットワーク設定で「HTTP(80)」と「HTTPS(443)」を追加
* 「インスタンスを起動」をクリック
* mv Downloads/sample-key.pem .ssh/ (「キー.pem」を.sshディレクトリに移動)
* cd .ssh/
* chmod 400 sample-key.pem
* ssh -i "sample-key.pem" ec2-user@ec2-??-??-???-???.ap-northeast-1.compute.amazonaws.com（AWS 接続画面記載のコードをコピペ）
***
#### nginxをセットアップ
* sudo amazon-linux-extras enable nginx1
* sudo yum -y install nginx #インストール
* sudo nginx -v #バージョン確認
* sudo systemctl enable nginx #nginx自動起動化
* sudo systemctl start nginx.service #起動
* sudo systemctl status nginx.service #起動確認
***
#### RDSを作成
* RDS画面にアクセス
* データベースページに移動し、データベースの作成をクリック
* エンジンでMySQLを選択
* テンプレートを無料利用枠に指定
* VPCセキュリティグループを作成し任意のセキュリティグループ名とec2インスタンスと同じアベイラビリティゾーンを指定
* 追加設定でデータベース名を入力する
* 一番下の「データベースの作成」ボタンをクリック
* 作成したDB（database-1)をクリック
* VPCセキュリティグループをクリック
* 「セキュリティグループ」をクリック→「インバウンドのルールを編集」をクリック
* EC2インスタンス作成時に設定したセキュリティグループを選択
***
#### 接続
* ターミナルからインスタンスに接続
* sudo yum update -y (パッケージのアップデート)
* sudo yum -y install mysql git httpd curl
* mysql -h database-1.cveawoxgeidk.ap-northeast-3.rds.amazonaws.com -P 3306 -u admin -p（パスワードはDBを作成するときに自分で入力したマスターパスワード）
***
#### 環境作成
* amazon-linux-extras list （利用できるパッケージの確認）
* sudo amazon-linux-extras install -y php8.2
* https://getcomposer.org/download/　（公式サイトに従ってComposer をインストールする）
* cd /var/www
* sudo chown ec2-user:ec2-user /var/www　（/var/wwwディレクトリの所有者とグループを変更）
* git clone https://github.com/???????.git　（デプロイしたいLaravelのプロジェクトをclone）
* sudo yum install -y php-xml　（Composerのパッケージをインストールするために必要なライブラリをインストール）
* cd アプリディレクトリ
* composer update
* composer install
* sudo yum install php php-devel php-opcache php-mbstring php-xml
* sudo systemctl start php-fpm.service
* sudo systemctl enable php-fpm.service
* systemctl status php-fpm.service
* https://knmts.com/become-engineer-30/ (記事を参考にphp-fpmとnginxの接続設定をする)
* クローンしたLaravelのプロジェクトディレクトリ内に移動して.envファイルの作成とAPP_KEYの生成を行う
* cd var/www/Laravelプロジェクト
* cp .env.example .env
* php artisan key:generate
* envファイルを設定して、RDSとアプリを繋げる
* php artisan migrate:fresh　--seed (migrateしてDBを作成する。)
* sudo chmod -R 777 storage  (app/storageの権限変更)







