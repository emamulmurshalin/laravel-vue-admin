<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Users Table</h3>

                        <div class="card-tools">
                            <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#Modal" @click="newUser()">Add New <i class="fas fa-user-plus fa-fw"></i></button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Type</th>
                                <th>Register Date</th>
                                <th>Modify</th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr v-for="user in users" :key="user.id">
                                <td>{{ user.id }}</td>
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>{{ user.type | capitalize }}</td>
                                <td>{{ user.created_at | myDate }}</td>
                                <td>
                                    <a href="#" @click="editUser(user)">
                                        <i class="fas fa-user-edit indigo"></i>
                                    </a>

                                    <a href="#" @click="deleteUser(user.id)">
                                        <i class="fas fa-trash-alt red"></i>
                                    </a>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="newModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode">Add New User</h5>
                        <h5 class="modal-title" v-show="editmode">Update User's Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode ? updateUser() : createUser()">
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="form.name" type="text" name="name"
                                       placeholder="Name"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.email" type="email" name="email"
                                       placeholder="Email Address"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                            </div>
                            <div class="form-group">
                                <select name="type" v-model="form.type" id="type" class="form-control" :class="{'is-invalid': form.errors.has('type')}">
                                    <option value="">Select User Roll</option>
                                    <option value="admin">Admin</option>
                                    <option value="user">Standard User</option>
                                    <option value="author">Author</option>
                                </select>
                                <has-error :form="form" field="type"></has-error>
                            </div>
                            <div class="form-group">
                                <textarea v-model="form.bio" name="bio" id="bio"
                                       placeholder="Short bio of user(Optional)"
                                          class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
                                <has-error :form="form" field="type"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.password" type="password" name="password"
                                       placeholder="Type Password"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                <has-error :form="form" field="password"></has-error>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                            <button v-show="!editmode" type="submit" class="btn btn-success">Create User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                editmode: false,
                users: {},
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
        methods:{
            updateUser(){
                this.$Progress.start();
                this.form.put('api/user/'+this.form.id)
                    .then(()=>{
                        Fire.$emit('afterAction');
                        $('#Modal').modal('hide');
                        Toast.fire({
                            icon: 'success',
                            title: 'Update User Information Successfully'
                        })
                        this.$Progress.finsh();
                    })
                    .catch(()=>{
                        this.$Progress.finish();
                    });
            },
            editUser(user){
                this.editmode= true;
                this.form.reset();
                $('#Modal').modal('show');
                this.form.fill(user);
            },
            newUser(){
                this.editmode= false;
                this.form.reset();
                $('#Modal').modal('show');
            },
            deleteUser(id){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    //Delete request sent
                    if (result.value) {
                        this.form.delete('api/user/' + id).then(() => {
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                            Fire.$emit('afterAction');
                        }).catch(() => {
                            Swal.fire(
                                'Failed!',
                                'This was something wrong.',
                                'warning'
                            )
                        });
                    }
                })
            },
            loadUsers(){
                axios.get('/api/user').then(({ data })=> (this.users = data.data));
            },
            createUser(){
                this.$Progress.start()
                this.form.post('api/user')
                    .then(()=>{
                        Fire.$emit('afterAction');
                        $('#Modal').modal('hide')

                        Toast.fire({
                            icon: 'success',
                            title: 'User Created Successfully'
                        })
                        this.$Progress.finish()
                    })
                    .catch(()=>{
                        this.$Progress.fail()
                    })
            }
        },
        created() {
            this.loadUsers();
            Fire.$on('afterAction',() => {
               this.loadUsers();
            });
            //setInterval(()=> this.loadUsers(), 3000);
        }
    }
</script>
