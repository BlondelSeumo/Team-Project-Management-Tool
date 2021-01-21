<form action="<?=base_url('settings/save-permissions-setting')?>" method="POST" id="setting-form">
<div class="card-body row">
                        <div class="alert alert-danger col-md-12 center">
                          <b>Note!</b> Admin always have all the permission. Here you can set permissions for users and clients.
                        </div>
                        <div class="col-md-6">
                          <div class="card-header">
                            <h4 class="card-title">Users permissions</h4>
                          </div>
                          <div class="form-group col-md-12">
                              <label class="d-block">Projects</label>

                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="project_view" name="project_view" value="<?=(isset($permissions->project_view) && !empty($permissions->project_view))?$permissions->project_view:0?>" <?=(isset($permissions->project_view) && !empty($permissions->project_view) && $permissions->project_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="project_view">View</label>
                              </div>

                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="project_create" name="project_create" value="<?=(isset($permissions->project_create) && !empty($permissions->project_create))?$permissions->project_create:0?>" <?=(isset($permissions->project_create) && !empty($permissions->project_create) && $permissions->project_create == 1)?'checked':''?>>
                                <label class="form-check-label" for="project_create">Create</label>
                              </div>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="project_edit" name="project_edit" value="<?=(isset($permissions->project_edit) && !empty($permissions->project_edit))?$permissions->project_edit:0?>" <?=(isset($permissions->project_edit) && !empty($permissions->project_edit) && $permissions->project_edit == 1)?'checked':''?>>
                                <label class="form-check-label" for="project_edit">Edit</label>
                              </div>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="project_delete" name="project_delete" value="<?=(isset($permissions->project_delete) && !empty($permissions->project_delete))?$permissions->project_delete:0?>" <?=(isset($permissions->project_delete) && !empty($permissions->project_delete) && $permissions->project_delete == 1)?'checked':''?>>
                                <label class="form-check-label" for="project_delete">Delete</label>
                              </div>
                          </div>
                          <div class="form-group col-md-12">
                              <label class="d-block">Tasks</label>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="task_view" name="task_view" value="<?=(isset($permissions->task_view) && !empty($permissions->task_view))?$permissions->task_view:0?>" <?=(isset($permissions->task_view) && !empty($permissions->task_view) && $permissions->task_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="task_view">View</label>
                              </div>

                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="task_create" name="task_create" value="<?=(isset($permissions->task_create) && !empty($permissions->task_create))?$permissions->task_create:0?>" <?=(isset($permissions->task_create) && !empty($permissions->task_create) && $permissions->task_create == 1)?'checked':''?>>
                                <label class="form-check-label" for="task_create">Create</label>
                              </div>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="task_edit" name="task_edit" value="<?=(isset($permissions->task_edit) && !empty($permissions->task_edit))?$permissions->task_edit:0?>" <?=(isset($permissions->task_edit) && !empty($permissions->task_edit) && $permissions->task_edit == 1)?'checked':''?>>
                                <label class="form-check-label" for="task_edit">Edit</label>
                              </div>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="task_delete" name="task_delete" value="<?=(isset($permissions->task_delete) && !empty($permissions->task_delete))?$permissions->task_delete:0?>" <?=(isset($permissions->task_delete) && !empty($permissions->task_delete) && $permissions->task_delete == 1)?'checked':''?>>
                                <label class="form-check-label" for="task_delete">Delete</label>
                              </div>
                          </div>
                        
                          <div class="form-group col-md-12">
                              <label class="d-block">ToDo 
                              </label>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="todo_view" name="todo_view" value="<?=(isset($permissions->todo_view) && !empty($permissions->todo_view))?$permissions->todo_view:0?>" <?=(isset($permissions->todo_view) && !empty($permissions->todo_view) && $permissions->todo_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="todo_view">View</label>
                              </div>
                          </div>
                          
                          <div class="form-group col-md-12">
                              <label class="d-block">Notes 
                              </label>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="notes_view" name="notes_view" value="<?=(isset($permissions->notes_view) && !empty($permissions->notes_view))?$permissions->notes_view:0?>" <?=(isset($permissions->notes_view) && !empty($permissions->notes_view) && $permissions->notes_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="notes_view">View</label>
                              </div>
                          </div>

                          
                          <div class="form-group col-md-12">
                              <label class="d-block">Chat 
                              </label>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="chat_view" name="chat_view" value="<?=(isset($permissions->chat_view) && !empty($permissions->chat_view))?$permissions->chat_view:0?>" <?=(isset($permissions->chat_view) && !empty($permissions->chat_view) && $permissions->chat_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="chat_view">View</label>
                              </div>
                          </div>

                          <div class="form-group col-md-12">
                              <label class="d-block">Users 
                                <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="" data-original-title="Only admin have permission to add, edit and delete users. You can make any user as admin they will get all this permissions by default."></i>
                              </label>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="user_view" name="user_view" value="<?=(isset($permissions->user_view) && !empty($permissions->user_view))?$permissions->user_view:0?>" <?=(isset($permissions->user_view) && !empty($permissions->user_view) && $permissions->user_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="user_view">View</label>
                              </div>
                          </div>
                          
                          <div class="form-group col-md-12">
                              <label class="d-block">Clients 
                                <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="" data-original-title="Only admin have permission to add, edit and delete clients. You can make any user as admin they will get all this permissions by default."></i>
                              </label>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_view" name="client_view" value="<?=(isset($permissions->client_view) && !empty($permissions->client_view))?$permissions->client_view:0?>" <?=(isset($permissions->client_view) && !empty($permissions->client_view) && $permissions->client_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_view">View</label>
                              </div>
                          </div>

                          <div class="form-group col-md-12">
                              <label class="d-block">Settings <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="" data-original-title="Settings have some some sensetive information about application. Make sure you have proper knowledge about what permission you are giving to the users."></i></label>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="setting_view" name="setting_view" value="<?=(isset($permissions->setting_view) && !empty($permissions->setting_view))?$permissions->setting_view:0?>" <?=(isset($permissions->setting_view) && !empty($permissions->setting_view) && $permissions->setting_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="setting_view">View</label>
                              </div>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="setting_update" name="setting_update" value="<?=(isset($permissions->setting_update) && !empty($permissions->setting_update))?$permissions->setting_update:0?>" <?=(isset($permissions->setting_update) && !empty($permissions->setting_update) && $permissions->setting_update == 1)?'checked':''?>>
                                <label class="form-check-label" for="setting_update">Update</label>
                              </div>
                          </div>
                        </div>


                        
                        <div class="col-md-6">
                          <div class="card-header">
                            <h4 class="card-title">Clients permissions</h4>
                          </div>
                          <div class="form-group col-md-12">
                              <label class="d-block">Projects</label>

                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_project_view" name="client_project_view" value="<?=(isset($clients_permissions->project_view) && !empty($clients_permissions->project_view))?$clients_permissions->project_view:0?>" <?=(isset($clients_permissions->project_view) && !empty($clients_permissions->project_view) && $clients_permissions->project_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_project_view">View</label>
                              </div>

                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_project_create" name="client_project_create" value="<?=(isset($clients_permissions->project_create) && !empty($clients_permissions->project_create))?$clients_permissions->project_create:0?>" <?=(isset($clients_permissions->project_create) && !empty($clients_permissions->project_create) && $clients_permissions->project_create == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_project_create">Create</label>
                              </div>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_project_edit" name="client_project_edit" value="<?=(isset($clients_permissions->project_edit) && !empty($clients_permissions->project_edit))?$clients_permissions->project_edit:0?>" <?=(isset($clients_permissions->project_edit) && !empty($clients_permissions->project_edit) && $clients_permissions->project_edit == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_project_edit">Edit</label>
                              </div>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_project_delete" name="client_project_delete" value="<?=(isset($clients_permissions->project_delete) && !empty($clients_permissions->project_delete))?$clients_permissions->project_delete:0?>" <?=(isset($clients_permissions->project_delete) && !empty($clients_permissions->project_delete) && $clients_permissions->project_delete == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_project_delete">Delete</label>
                              </div>
                          </div>
                          <div class="form-group col-md-12">
                              <label class="d-block">Tasks</label>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_task_view" name="client_task_view" value="<?=(isset($clients_permissions->task_view) && !empty($clients_permissions->task_view))?$clients_permissions->task_view:0?>" <?=(isset($clients_permissions->task_view) && !empty($clients_permissions->task_view) && $clients_permissions->task_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_task_view">View</label>
                              </div>

                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_task_create" name="client_task_create" value="<?=(isset($clients_permissions->task_create) && !empty($clients_permissions->task_create))?$clients_permissions->task_create:0?>" <?=(isset($clients_permissions->task_create) && !empty($clients_permissions->task_create) && $clients_permissions->task_create == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_task_create">Create</label>
                              </div>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_task_edit" name="client_task_edit" value="<?=(isset($clients_permissions->task_edit) && !empty($clients_permissions->task_edit))?$clients_permissions->task_edit:0?>" <?=(isset($clients_permissions->task_edit) && !empty($clients_permissions->task_edit) && $clients_permissions->task_edit == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_task_edit">Edit</label>
                              </div>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_task_delete" name="client_task_delete" value="<?=(isset($clients_permissions->task_delete) && !empty($clients_permissions->task_delete))?$clients_permissions->task_delete:0?>" <?=(isset($clients_permissions->task_delete) && !empty($clients_permissions->task_delete) && $clients_permissions->task_delete == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_task_delete">Delete</label>
                              </div>
                          </div>
                        
                          <div class="form-group col-md-12">
                              <label class="d-block">ToDo 
                              </label>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_todo_view" name="client_todo_view" value="<?=(isset($clients_permissions->todo_view) && !empty($clients_permissions->todo_view))?$clients_permissions->todo_view:0?>" <?=(isset($clients_permissions->todo_view) && !empty($clients_permissions->todo_view) && $clients_permissions->todo_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_todo_view">View</label>
                              </div>
                          </div>
                          
                          <div class="form-group col-md-12">
                              <label class="d-block">Notes 
                              </label>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_notes_view" name="client_notes_view" value="<?=(isset($clients_permissions->notes_view) && !empty($clients_permissions->notes_view))?$clients_permissions->notes_view:0?>" <?=(isset($clients_permissions->notes_view) && !empty($clients_permissions->notes_view) && $clients_permissions->notes_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_notes_view">View</label>
                              </div>
                          </div>

                          
                          <div class="form-group col-md-12">
                              <label class="d-block">Chat 
                              </label>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_chat_view" name="client_chat_view" value="<?=(isset($clients_permissions->chat_view) && !empty($clients_permissions->chat_view))?$clients_permissions->chat_view:0?>" <?=(isset($clients_permissions->chat_view) && !empty($clients_permissions->chat_view) && $clients_permissions->chat_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_chat_view">View</label>
                              </div>
                          </div>

                          <div class="form-group col-md-12">
                              <label class="d-block">Users 
                                <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="" data-original-title="Only admin have permission to add, edit and delete users. You can make any user as admin they will get all this permissions by default."></i>
                              </label>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_user_view" name="client_user_view" value="<?=(isset($clients_permissions->user_view) && !empty($clients_permissions->user_view))?$clients_permissions->user_view:0?>" <?=(isset($clients_permissions->user_view) && !empty($clients_permissions->user_view) && $clients_permissions->user_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_user_view">View</label>
                              </div>
                          </div>
                          
                          <div class="form-group col-md-12">
                              <label class="d-block">Clients 
                                <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="" data-original-title="Only admin have permission to add, edit and delete clients. You can make any user as admin they will get all this permissions by default."></i>
                              </label>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_client_view" name="client_client_view" value="<?=(isset($clients_permissions->client_view) && !empty($clients_permissions->client_view))?$clients_permissions->client_view:0?>" <?=(isset($clients_permissions->client_view) && !empty($clients_permissions->client_view) && $clients_permissions->client_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_client_view">View</label>
                              </div>
                          </div>

                          <div class="form-group col-md-12">
                              <label class="d-block">Settings <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="" data-original-title="Settings have some some sensetive information about application. Make sure you have proper knowledge about what permission you are giving to the users."></i></label>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_setting_view" name="client_setting_view" value="<?=(isset($clients_permissions->setting_view) && !empty($clients_permissions->setting_view))?$clients_permissions->setting_view:0?>" <?=(isset($clients_permissions->setting_view) && !empty($clients_permissions->setting_view) && $clients_permissions->setting_view == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_setting_view">View</label>
                              </div>
                              
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="client_setting_update" name="client_setting_update" value="<?=(isset($clients_permissions->setting_update) && !empty($clients_permissions->setting_update))?$clients_permissions->setting_update:0?>" <?=(isset($clients_permissions->setting_update) && !empty($clients_permissions->setting_update) && $clients_permissions->setting_update == 1)?'checked':''?>>
                                <label class="form-check-label" for="client_setting_update">Update</label>
                              </div>
                          </div>
                        </div>

                      </div>
                      <?php if ($this->ion_auth->is_admin() || permissions('setting_update') || $this->ion_auth->in_group(3)){ ?>
                        <div class="card-footer bg-whitesmoke text-md-right">
                          <button class="btn btn-primary savebtn">Save Changes</button>
                        </div>
                      <?php } ?>
                      <div class="result"></div>
                    </form>