<template>
  <div>
    <el-divider>
      {{ c.tipo_consulta }}
    </el-divider>
    <el-row style="font-weight: 600; text-align: center;">
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
        Ultimo Paciente
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
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      c: {
        type: Object,
        default: function() {
          return {}
        }
      }
    },
    data () {
      return {
      }
    }
  }
</script>
<style scoped>
</style>
