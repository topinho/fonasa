<template>
  <div v-loading="loading">
    <el-row :gutter="20">
      <el-col v-for="consulta in consultas" :key="consulta.id" :xs="24" :sm="24" :md="8" :lg="8" :xl="8">
        <el-card class="box-card">
          <consulta-slot :c="consulta" />
          <!--<el-row style="font-weight: 600; text-align: center;">
            <el-col :xs="12" :sm="12" :md="12" :lg="12" :xl="12">
              <label>Especialista</label>
            </el-col>
            <el-col :xs="6" :sm="6" :md="6" :lg="6" :xl="6">
              <label>Atenciones</label>
            </el-col>
            <el-col :xs="6" :sm="6" :md="6" :lg="6" :xl="6">
              <label>Estado</label>
            </el-col>
          </el-row>
          <el-row style="text-align: center;">
            <el-col :xs="12" :sm="12" :md="12" :lg="12" :xl="12">
              <span>{{ c.nombre_especialista }}</span>
            </el-col>
            <el-col :xs="6" :sm="6" :md="6" :lg="6" :xl="6">
              <span>{{ c.cantidad_pacientes }}</span>
            </el-col>
            <el-col :xs="6" :sm="6" :md="6" :lg="6" :xl="6">
              <span>{{ c.estado }}</span>
            </el-col>
          </el-row>
          <div style="padding-top: 10px;">
            <el-divider>
              Paciente En Atencion
            </el-divider>
            <el-row style="font-weight: 600; text-align: center;">
              <el-col :xs="12" :sm="12" :md="12" :lg="12" :xl="12">
                <label>Nombre</label>
              </el-col>
              <el-col :xs="6" :sm="6" :md="6" :lg="6" :xl="6">
                <label>Edad</label>
              </el-col>
              <el-col :xs="6" :sm="6" :md="6" :lg="6" :xl="6">
                <label>Tipo</label>
              </el-col>
            </el-row>
            <el-row style="text-align: center;">
              <el-col :xs="12" :sm="12" :md="12" :lg="12" :xl="12">
                <span>{{c.paciente_nombre}}</span>
              </el-col>
              <el-col :xs="6" :sm="6" :md="6" :lg="6" :xl="6">
                <span>{{c.paciente_edad}}</span>
              </el-col>
              <el-col :xs="6" :sm="6" :md="6" :lg="6" :xl="6">
                <span>{{c.paciente_tipo}}</span>
              </el-col>
            </el-row>
          </div>-->
        </el-card>
      </el-col>
    </el-row>
  </div>
</template>

<script>

  import ConsultaSlot from './ConsultaSlot.vue'

  export default {
    components: {
      ConsultaSlot
    },
    props: {
      consultas: {
        type: Array,
        default: function() {
          return []
        }
      },
      loading: {
        type: Boolean,
        default:false
      }
    },
    data () {
      return {
      }
    }
  }
</script>
<style scoped>
  .box-card {
    padding: 0px;
    height: 230px;
  }
  .el-divider {
    margin-top: 12px;
    margin-bottom: 15px;
  }
</style>
