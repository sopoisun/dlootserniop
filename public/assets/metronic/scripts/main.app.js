var MainApp = function(){
    var notifSound;
    var logoutAction = function(){
        $("#logoutLink").click(function(e){
            if( confirm('Mau log out ?') ){
                CFirebase.logout();
                window.location = $(this).attr('href');
            }
            e.preventDefault();
        });
    }

    var resetUserPassword = function(){
        $(document).on("click", ".btnResetPasswordUser", function(e){
            var namaUser = $(this).data('namauser');
            if( confirm('Yakin reset password '+namaUser+' ?') ){
                // Reset password database + firebase
                var href = ($(this).attr('href'));

                $.ajax({
                    url: href,
                    method: 'POST',
                    data:{ _token: appToken },
                    success: function(res){
                        toastr.options.closeButton = true;
                        toastr.options.positionClass = "toast-bottom-right";
                        if( res.code == 1 ){
                            CFirebase.changeUserPassword(res.uid_token, res.pwd, function(success){
                                toastr.success(res.message);
                            }, function(error){
                                toastr.error(error.message);
                            });
                        }else{
                            toastr.error(res.message);
                        }
                    }
                })
            }

            e.preventDefault();
        });
    }

    var createFirebaseUser = function(){
        $(document).on("click", ".btnCreateFirebaseUser", function(e){
            var namaUser = $(this).data('namauser');
            if( confirm('Yakin buat akun firebase untuk '+namaUser+' ? Password akun akan reset ke password default.') ){
                var _this   = $(this);
                var href    = ($(this).attr('href'));
                var email   = (_this.parent().parent().find('td').eq(1).text());

                $.ajax({
                    url: href, // set password to default password
                    method: 'POST',
                    data:{ _token: appToken },
                    success: function(res){
                        toastr.options.closeButton = true;
                        toastr.options.positionClass = "toast-bottom-right";

                        if( res.code == 1 ){
                            CFirebase.createUser(email, res.pwd)
                                .then(function(user){
                                    var uid_firebase = user.uid;
                                    secondFirebaseApp.auth().signOut();

                                    // update uid_firebase user to database
                                    $.ajax({
                                        url: baseUrl+"/ajax/user/set-uid-firebase", // set uid firebase to selected account
                                        method: "POST",
                                        data: { _token: appToken, email: email, uid_firebase: uid_firebase },
                                        success: function(res2){
                                            if( res2.code ){
                                                CFirebase.writeUserToDB(uid_firebase)
                                                    .then(function(){
                                                        _this.parent().html(res2.view);
                                                        if( res.is_admin == true ){ // as admin
                                                            CFirebase.writeUserToDBasAdmin(uid_firebase)
                                                                .then(function(){
                                                                    //_this.remove();
                                                                    toastr.success("Sukses buat akun firebase.");
                                                                });
                                                        }else{
                                                            //_this.remove();
                                                            toastr.success("Sukses buat akun firebase.");
                                                        }
                                                    });
                                            }else{
                                                toastr.error(res2.message);
                                            }
                                        }
                                    });
                                })
                                .catch(function(err){
                                    toastr.error(err.message);
                                });
                        }else{
                            toastr.error(res.message);
                        }
                    }
                });
            }

            e.preventDefault();
        });
    }

    var deleteFirebaseUser = function(){
        $(document).on('click', '.btnDeleteFirebaseUser', function(e){
            var namaUser = $(this).data('namauser');
            if( confirm('Yakin hapus '+namaUser+" ?") ){
                toastr.options.closeButton = true;
                toastr.options.positionClass = "toast-bottom-right";

                var url = ($(this).attr('href'));
                var urlSplit = url.split('/');
                var userId = urlSplit[urlSplit.length - 1];
                //console.log(userId);

                $.ajax({
                    url: baseUrl+"/ajax/user/get-token-firebase-user",
                    method: "POST",
                    data: { _token: appToken, id: userId },
                    success: function(res){
                        if( res.uid != 0 && res.token != 0 ){
                            CFirebase.deleteUser(res.token, res.uid, function(success){
                                window.location = url;
                            }, function(error){
                                toastr.error(error.message);
                            });
                        }else{
                            window.location = url;
                        }
                    }
                });
            }

            e.preventDefault();
        });
    };

    var orderSnapshot = function(){
        if( typeof app.notification !== undefined && app.notification == true ){
            var date = new Date();
            var _date = date.getFullYear()
                + '-' + ('0' + (date.getMonth()+1)).slice(-2)
                + '-' + ('0' + date.getDate()).slice(-2);

            //console.log(_date);

            firebaseApp.database().ref().child('queues').child(_date)
                .on('child_added', snap => {
                    //console.log("========")
                    //console.log("child add "+snap.key)
                    //console.log(snap.val());
                    //console.log("========")
                    var notifyData = snap.val();
                    //console.log(notifyData.nstatus);
                    if( typeof notifyData.state === 'undefined' && localStorage.getItem(_date+"."+notifyData.id) === null ){
                        ShowNotification(_date, notifyData);
                    }
                });
        }
    }

    var ShowNotification = function(date, data){
        //console.log(data);
        var notify = new Notification("Order baru dari "+data.karyawan.nama, {
            body: "Dari "+data.places,
            icon: baseUrl+'/assets/firebase/order.png',
            //sound: baseUrl+'/assets/firebase/notification.mp3',
            tag: data.id,
            data: data,
            requireInteraction: true
        });

        notify.onclick = function(){
            console.log(this);
            var _url = baseUrl+"/ajax/order/queue/"+this.data.id+"/changestate";
            $.ajax({
                url: _url,
                method: "POST",
                data:{ state: 1, _token: appToken },
                success: function(res){
                    CFirebase.updateQueueOrder(date, this.data.id, { state : 1 }).then(function(){
                        notify.close();
                    });
                }
            });
        };

        notifSound.play();
        localStorage.setItem(date+"."+data.id, true);
    }

    var activateNotification = function(){
        if( !window.Notification){
            alert('Browser tidak support untuk menerima notifikasi!')
        }else{
            Notification.requestPermission(function(p){
                if( p === 'denied' ){
                    //alert('Anda menolak notifikasi, anda tidak akan mendapat notifikasi ketika ada order!')
                }else if( p === 'granted' ){
                    //alert('Anda menyetujui notifikasi, anda akan mendapat notifikasi ketika ada order!')
                }
            });
        }

        notifSound = new Audio(baseUrl+'/assets/firebase/notification.mp3');
        notifSound.loop = false;
    }

    return {
        init: function(){
            activateNotification();
            logoutAction();
            resetUserPassword();
            createFirebaseUser();
            deleteFirebaseUser();
            orderSnapshot();

            /*setTimeout(function(){
                CFirebase.getUserToken(function(res){
                    console.log(res)
                }, function(err){
                    console.error(err);
                });
            }, 5000);*/
        }
    };
}();
