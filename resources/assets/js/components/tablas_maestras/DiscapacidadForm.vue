<template>
    <v-container fluid grid-list-md text-xs-center>
    <v-layout row justify-center>
    <v-flex xs12>
        <v-form ref="form" v-model="valido" lazy-validation>
        <v-card>
            
        <v-card-title class="light-blue darken-3 white--text">
            <h2>Discapacidad</h2>
        </v-card-title>
        
        <v-card-text>
        <v-layout wrap>
        
            <v-flex xs12 >
            <v-text-field
                v-model="form.nb_discapacidad"
                :rules="rules.requerido"
                label="Nombre de la Discapacidad"
                placeholder="Indique Nombre"
                required
            ></v-text-field>
            </v-flex>

            <v-flex xs12 sm6>
                <v-select
                :items="listas.tipoDiscapacidad"
                item-text="nb_tipo_discapacidad"
                item-value="id_tipo_discapacidad"
                v-model="form.id_tipo_discapacidad"
                :rules="rules.select"
                label="Tipo Discapacidad"
                required
                ></v-select>
            </v-flex>

            <v-flex xs12>  
                <v-select
                :items="listas.status"
                item-text="nb_status"
                item-value="id_status"
                v-model="form.id_status"
                :rules="rules.select"
                label="Estatus de la Discapacidad"
                required
                ></v-select>
            </v-flex>

            <v-flex xs12 >
                <v-text-field
                    v-model="form.tx_observaciones"
                    label="Observaciones"
                    placeholder="Indique Observaciones"
                ></v-text-field>
            </v-flex>
                    
            </v-layout>
            </v-card-text>
            
            <v-card-actions>

                <form-buttons
                    @update="update"
                    @store="store"
                    @clear="clear"
                    @cancel="cancel"
                    :btnAccion="btnAccion"
                    :valido="valido"
                ></form-buttons>   

            </v-card-actions>
                                
        </v-card>
        </v-form>
    </v-flex>
    </v-layout>
    </v-container>
    
</template>

<script>

import withSnackbar from '../mixins/withSnackbar';
import formHelper from '../mixins/formHelper';

export default {
    mixins: [ formHelper, withSnackbar ],
    data () {
        return {
            tabla: 'discapacidad',
            form:{
                id_discapacidad: '',
                nb_discapacidad: '',
                id_tipo_discapacidad: '',
                id_status: '',
                tx_observaciones: '',
                id_usuario:''
            },
            listas:{
                tipoDiscapacidad:  [],
                status:     ['/grupo/GRAL']
            },
            
        }
    },
    methods:{
        
        update()
        {
            this.form.id_usuario = this.$store.getters.user.id_usuario;
            if (this.$refs.form.validate()) 
            {           
                axios.put(this.basePath + this.form.id_discapacidad, this.form)
                .then(respuesta => {
                    this.showMessage(respuesta.data.msj)
                    this.cancel();
                })
                .catch(error => {
                    this.showError(error);
                    
                })
            }
        },
        store()
        {
            this.form.id_usuario = this.$store.getters.user.id_usuario;
            if (this.$refs.form.validate()) 
            {
                axios.post(this.basePath, this.form)
                .then(respuesta => {
                    this.showMessage(respuesta.data.msj)
                    this.$emit('cerrarModal');
                })
                .catch(error => {
                    this.showError(error);
                })
            }
        }
    }
    
}

</script>