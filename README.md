# Rese
***
## アプリケーション概要
飲食店の予約システム
ログイン後、店舗の予約やお気に入りを追加、レビューを投稿が可能
管理者権限と店舗代表者権限を切り分けた
リマインドメールに添付されるQRコードで予約データが'来店'に切り替わる
決済機能はStripeを使用

## アプリケーションURL
ec2-54-249-162-21.ap-northeast-1.compute.amazonaws.com

## 機能一覧
* ログイン機能
* メール認証
* お気に入り追加/削除
* 予約追加/変更
* 検索、並び替え、レビュー
* リマインドメール送信
* QRコードで予約認証
* 決済機能
* 管理者権限で店舗代表者作成
* ユーザー一覧閲覧
* お知らせメール送信
* 店舗代表者権限で店舗情報の作成/更新
* 予約確認/変更/削除 
* csvインポートで新規店舗追加

## 使用技術
* docker
* Laravel 8.x
* PHP 7.4
* laravel-fortify
* laravel-permission
* Stripe

## テーブル設計・ER図
![atte drawio](https://github.com/user-attachments/assets/59d19922-034a-472c-b7cf-d380fad1918e)


***
## 環境構築

### セットアアップ
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
