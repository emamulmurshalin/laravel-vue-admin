<style>
    .widget-user-header{
        background-postion: center center !important;
        background-size: cover;
        height: 250px !important;
    }
</style>
<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                    <!-- Widget: user widget style 1 -->
                    <div class="card card-widget widget-user">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="widget-user-header text-white"
                             style="background-image:url('/img/background.jpg')">
                            <h3 class="widget-user-username text-right"></h3>
                            <h5 class="widget-user-desc text-right">Web Designer</h5>
                        </div>
                        <div class="widget-user-image">
                            <img class="img-circle" src="/img/profile.png" alt="User Avatar">
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header">3,200</h5>
                                        <span class="description-text">SALES</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header">13,000</h5>
                                        <span class="description-text">FOLLOWERS</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4">
                                    <div class="description-block">
                                        <h5 class="description-header">35</h5>
                                        <span class="description-text">PRODUCTS</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                    </div>
                    <!-- /.widget-user -->
            </div>


            <div class="col-md-12">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Activity</a></li>
                           <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="activity">
                                <div class="activity activity-inverse">
                                    <h2 style="text-align:center; font-weight:bold;">User Activity Here</h2>
                                </div>
                            </div>

                            <div class="tab-pane" id="settings">
                                <form class="form-horizontal">
                                    <div class="form-group row">
                                        <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" v-model="form.name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" id="inputName" placeholder="Name">
                                            <has-error :form="form" field="name"></has-error>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                        <div class="col-sm-10">
                                            <input type="email" v-model="form.email" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }" id="inputEmail" placeholder="Email">
                                            <has-error :form="form" field="email"></has-error>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputBio" class="col-sm-2 col-form-label">Bio</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" id="inputBio" v-model="form.bio" placeholder="Bio"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPhoto" class="col-sm-2 col-form-label">Profile Photo</label>
                                        <div class="col-sm-10">
                                            <input type="file" @change="updateProfile" class="form-control" id="inputPhoto">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" id="inputPassword" v-model="form.password" placeholder="Password(Leave empty if not changing)">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10">
                                            <button @click.prevent="updateProfileInfo" type="submit" class="btn btn-success">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return  {
                form: new Form({
                    id: '',
                    name : '',
                    email : '',
                    password : '',
                    type : '',
                    bio : '',
                    photo: ''
                })
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods:{
            updateProfileInfo(){
                this.$Progress.start();
                this.form.put('api/profile/')
                    .then(() => {

                        this.$Progress.finish();
                    })
                    .catch(() => {

                        this.$Progress.fail();
                    });
            },
            updateProfile(e){
                //console.log('jjjj');
                let file = e.target.files[0];
                //console.log(file);
                let reader = new FileReader();
                if(file['size'] < 2111775){
                    reader.onloadend = (file) => {
                        this.form.photo= reader.result;
                        // console.log('RESULT', reader.result)
                    }
                    reader.readAsDataURL(file);
                }else{
                    Swal.fire({
                        title: 'Opps...',
                        text: "You are uploading large picture",
                        icon: 'warning',
                    })
                }
            }
        },
        created(){
            axios.get('/api/profile')
                .then(({ data })=> (this.form.fill(data)));
        }
    }
</script>
