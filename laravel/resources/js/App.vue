<template>
  <div id="app">
    <div v-loading="loading" class="app-container">
      <el-row>
        <el-col :xs="16" :sm="16" :md="18" :lg="20" :xl="20">
          <el-divider>
            Consultas del Hospital
          </el-divider>
        </el-col>
        <el-col :xs="8" :sm="8" :md="6" :lg="4" :xl="4">
          <el-button-group style="float: right;">
            <el-tooltip class="item" effect="dark" content="Mostrar Consulta Con mas Atenciones" placement="right">
              <el-button
                type="primary"
                size="mini"
                icon="el-icon-s-data"
                @click="consultaMasAtenciones"
              />
            </el-tooltip>
          </el-button-group>
        </el-col>  
        <el-dialog :visible.sync="mostrarConsultaMasAtenciones" title="Consulta con mas atenciones">
          <consulta-slot v-loading="loading" :c="dataConsulta" />
        </el-dialog>
      </el-row>
      <consultas v-if="dataConsultas" :consultas="dataConsultas"/>
      <div style="padding-top: 10px;">  
        <el-button
          type="primary"
          style="width: 100%"
          size="mini"
          icon="el-icon-help"
          :disabled="dataPacientesEspera.length === 0"
          @click="liberarConsultas"
        >
          Liberar Consultas
        </el-button>
      </div>
      <el-row style="padding-top: 10px;">
        <el-col :xs="16" :sm="16" :md="18" :lg="20" :xl="20">
          <el-divider>
            Listado de Pacientes
          </el-divider>
        </el-col>
        <el-col :xs="8" :sm="8" :md="6" :lg="4" :xl="4">
          <el-button-group style="float: right;">
            <el-tooltip class="item" effect="dark" content="Agregar un Paciente" placement="right">
              <el-button
                type="success"
                size="mini"
                icon="el-icon-plus"
                :disabled="true"
                @click="mostrarAgregarPaciente = true"
              />
            </el-tooltip>
          </el-button-group>
        </el-col>
        <el-dialog :visible.sync="mostrarAgregarPaciente" title="Agregar Paciente">
          <p>Agregar Paciente</p>
        </el-dialog>
      </el-row>
      <el-row :gutter="20">
        <el-col :xs="24" :sm="24" :md="16" :lg="8" :xl="8">
          <h2>Lista de Espera</h2>
          <espera :pacientes="dataPacientesEspera" :loading="loading" />
        </el-col> 
        <el-col :xs="24" :sm="24" :md="16" :lg="16" :xl="16">    
          <el-tabs v-model="activeName" @tab-click="getDataPacientes" style="padding-top: 10px;">
            <el-tab-pane v-for="i in listados" :key="i.objecto" :label="i.component" :name="i.objeto">
              <span slot="label">
                <!--<i :class="i.icon" />-->
                {{ i.component }}
              </span>
              <component
                :is="i.component"
                v-if="i.component"
                :pacientes="dataPacientesPendientes"
                :loading="loading"
              />
            </el-tab-pane>
          </el-tabs>
        </el-col>
      </el-row>
      <el-row :gutter="20" style="padding-top: 10px;">
        <el-col :span="12">  
          <el-button
            type="success"
            style="width: 100%"
            size="mini"
            icon="el-icon-check"
            :disabled="dataPacientesPendientes.length === 0"
            @click="atenderPacientes"
          >
            Atender Pacientes
          </el-button>
        </el-col>
        <el-col :span="12">  
          <el-button
            type="info"
            style="width: 100%"
            size="mini"
            icon="el-icon-magic-stick"
            :disabled="dataPacientesPendientes.length === 0"
            @click="optimizarAtencion"
          >
            Optimizar Atencion
          </el-button>
        </el-col>
      </el-row>
    </div>
  </div>
</template>
<script>
  import Consultas from './components/Consultas.vue'
  import Espera from './components/Espera.vue'
  import Pendientes from './components/Pendientes.vue'
  import Fumadores from './components/Fumadores.vue'
  import Ancianos from './components/Ancianos.vue'
  import Urgentes from './components/Urgentes.vue'
  import Optimizar from './components/Optimizar.vue'
  import MayorRiesgoQue from './components/MayorRiesgoQue.vue'
  import ConsultaSlot from './components/ConsultaSlot.vue'

  export default {
    name: 'app',
    components: {
      Consultas,
      Espera,
      Pendientes,
      Fumadores,
      Ancianos,
      Urgentes,
      Optimizar,
      MayorRiesgoQue,
      ConsultaSlot
    },
    data () {
      return {
        visible: false,
        mostrarConsultaMasAtenciones: false,
        mostrarAgregarPaciente: false,
        loading: false,
        activeName: 'pendientes',
        dataConsultas: [],
        dataPacientesPendientes: [],
        dataPacientesEspera: [],
        listados: [
          { component: 'Pendientes', label: 'Pendientes', icon: 'fas pi-fa-user', objeto: 'pendientes' },
          { component: 'Fumadores', label: 'Jovenes Fumadores', icon: 'fas pi-fa-coins', objeto: 'fumadores' },
          { component: 'Ancianos', label: 'Pendientes', icon: 'fas pi-fa-coins', objeto: 'ancianos' },
          { component: 'Urgentes', label: 'Urgentes', icon: 'fas pi-fa-coins', objeto: 'urgentes' },
          { component: 'Optimizar', label: 'Optimizar', icon: 'fas pi-fa-coins', objeto: 'optimizar' },
          { component: 'MayorRiesgoQue', label: 'Mayor Riesgo Que', icon: 'fas pi-fa-coins', objeto: 'mayor_riesgo_que' }
        ],
        dataConsulta: {},
        dataAtenciones: []
      }
    },
    created() {
      this.getDataInicio()
    },
    methods: {
      getDataInicio() {
        this.loading = true
        this.axios
          .get(`http://localhost:30019/api/inicio`)
            .then((response) => {
              this.loading = false
              this.dataConsultas = response.data.consultas;
              this.dataPacientesPendientes = response.data.pacientes_pendientes;
              this.dataPacientesEspera = response.data.pacientes_espera;
              // console.log(response.data);
          });
      },
      getDataPacientes() {
        this.loading = true
        // console.log('activeName', this.activeName)
        this.axios
          .get(`http://localhost:30019/api/pacientes`, { params: { tabs: this.activeName }} )
            .then((response) => {
              this.loading = false
              this.dataPacientesPendientes = response.data;
              // console.log(response.data);
          });
      },
      atenderPacientes() {
        alert("Atendiendo paciente por prioridad")
        this.loading = true
        // console.log('activeName', this.activeName)
        this.axios
          .post(`http://localhost:30019/api/atenciones`)
            .then((response) => {
              this.loading = false
              this.dataConsultas = response.data.consultas;
              this.dataPacientesPendientes = response.data.pacientes_pendientes;
              this.dataPacientesEspera = response.data.pacientes_espera;
          });
      },
      liberarConsultas() {
        alert("Liberar Consultas... Se buscaran pacientes en lista de espera segun consulta desocupada")
        this.loading = true
        // console.log('activeName', this.activeName)
        this.axios
          .post(`http://localhost:30019/api/consultas/liberar`)
            .then((response) => {
              this.loading = false
              this.dataConsultas = response.data.consultas;
              this.dataPacientesPendientes = response.data.pacientes_pendientes;
              this.dataPacientesEspera = response.data.pacientes_espera;
          });
      },
      consultaMasAtenciones() {
        this.mostrarConsultaMasAtenciones = true
        this.loading = true
        this.axios
          .get(`http://localhost:30019/api/consultas/mas-atenciones`)
            .then((response) => {
              this.loading = false
              this.dataConsulta = response.data.consulta;
              this.dataConsultaAtenciones = response.data.atenciones;
          });
      },
      optimizarAtencion() {
        alert("Optimizar Atencion. Segun las consultas disponibles, busca los pacientes de mayor riesgo y prioridad para darlos por atendidos. Luego libera las consultas.")
        this.loading = true
        // console.log('activeName', this.activeName)
        this.axios
          .post(`http://localhost:30019/api/atenciones/optimizar`)
            .then((response) => {
              this.loading = false
              this.dataConsultas = response.data.consultas;
              this.dataPacientesPendientes = response.data.pacientes_pendientes;
              this.dataPacientesEspera = response.data.pacientes_espera;
          });
      }
    }
  };
</script>
<style lang="scss">
.app-container {
  padding: 20px;
}

.grid-tabla {
  p {
    margin: 0;
  }
  &.rows {
    p {
      font-weight: 600;
      font-size: 13px;
      background-color: #E5E9F2;
      border-radius: 3px;
      min-height: 20px;
      text-align: left;
      padding-top: 4px;
      padding-bottom: 4px;
      vertical-align: middle;
      color: #666;
    }
    .link:hover {
      color: #007AD9;
      cursor: pointer;
    }
  }
  &.filas {
    .el-col {
      // font-weight: 600;
      font-size: 13px;
      // background-color: #E5E9F2;
      min-height: 20px;
      text-align: left;
      padding-top: 4px;
      padding-bottom: 4px;
      vertical-align: middle;
      color: #666;
      .link:hover {
        color: #007AD9;
        cursor: pointer;
      }
      // font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
    }
  }
}
// button-custom
.el-button-custom {
  width: 32px;
  height: 28px;
  padding-top: 4px; 
  padding-bottom: 4px;
  padding-right: 8px;
  padding-left: 8px;
  i {
    font-size: 16px;
  }
}
.el-divider {
  margin-top: 12px;
  margin-bottom: 15px;
}
</style>