<template>
  <div v-loading="loading">
    <el-divider>
      Optimizar Atencion, Pacientes Pendientes y en Espera por orden de Urgencia (Riesgo)
    </el-divider>
    <el-row style="font-weight: 600; text-align: center;">
      <el-col :xs="3" :sm="3" :md="3" :lg="3" :xl="3">
        HM
        <i class="el-icon-top" @click="sortList('numero_historia_clinica', 'up')" />
        <i class="el-icon-bottom" @click="sortList('numero_historia_clinica', 'down')" />
      </el-col>
      <el-col :xs="9" :sm="9" :md="9" :lg="9" :xl="9">
        Nombre
        <i class="el-icon-top" @click="sortList('nombre', 'up')" />
        <i class="el-icon-bottom" @click="sortList('nombre', 'down')" />
      </el-col>
      <el-col :xs="3" :sm="3" :md="3" :lg="3" :xl="3">
        Edad
        <i class="el-icon-top" @click="sortList('edad', 'up')" />
        <i class="el-icon-bottom" @click="sortList('edad', 'down')" />
      </el-col>
      <el-col :xs="3" :sm="3" :md="3" :lg="3" :xl="3">
        Tipo
        <i class="el-icon-top" @click="sortList('tipo_paciente', 'up')" />
        <i class="el-icon-bottom" @click="sortList('tipo_paciente', 'down')" />
      </el-col>
      <el-col :xs="3" :sm="3" :md="3" :lg="3" :xl="3">
        Riesgo
        <i class="el-icon-top" @click="sortList('riesgo', 'up')" />
        <i class="el-icon-bottom" @click="sortList('riesgo', 'down')" />
      </el-col>
      <el-col :xs="3" :sm="3" :md="3" :lg="3" :xl="3">
        Prior.
        <i class="el-icon-top" @click="sortList('prioridad', 'up')" />
        <i class="el-icon-bottom" @click="sortList('prioridad', 'down')" />
      </el-col>
    </el-row>
    <el-row style="font-weight: 600; text-align: center;">
      <el-col :xs="3" :sm="3" :md="3" :lg="3" :xl="3">
        <el-input
          v-model="filters['numero_historia_clinica']"
          placeholder="# Historia Medica"
          size="mini"
          style="width: 100%;"
          class="el-input-center"
        />
      </el-col>
      <el-col :xs="9" :sm="9" :md="9" :lg="9" :xl="9">
        <el-input
          v-model="filters['nombre']"
          placeholder="Nombre"
          size="mini"
          style="width: 100%;"
          class="el-input-center"
        />
      </el-col>
      <el-col :xs="3" :sm="3" :md="3" :lg="3" :xl="3">
        <el-input
          v-model="filters['edad']"
          placeholder="Edad"
          size="mini"
          style="width: 100%;"
          class="el-input-center"
        />
      </el-col>
      <el-col :xs="3" :sm="3" :md="3" :lg="3" :xl="3">
        <el-select
            v-model="filters['item']"
            clearable
            style="width: 100%;"
            size="mini"
            placeholder="Tipo Item"
          >
            <el-option
              v-for="item in tipos_pacientes"
              :key="item.nombre"
              :label="item.nombre"
              :value="item.nombre"
            />
          </el-select>
      </el-col>
      <el-col :xs="3" :sm="3" :md="3" :lg="3" :xl="3">
        -
      </el-col>
      <el-col :xs="3" :sm="3" :md="3" :lg="3" :xl="3">
        -
      </el-col>
    </el-row>
    <el-row
      class="panel-ficha grid-tabla filas"
      v-for="(c, i) in pacientesFiltered"
      :key="c.id"
      :style="{background: i % 2 === 0 ? '#E5E9F2' : ''}"
    >
      <el-col :xs="3" :sm="3" :md="3" :lg="3" :xl="3">
        <p style="text-align: center;">
          {{ c.numero_historia_clinica }}
        </p>
      </el-col>
      <el-col :xs="9" :sm="9" :md="9" :lg="9" :xl="9">
        <p>
          {{ c.nombre }}
        </p>
      </el-col>
      <el-col :xs="3" :sm="3" :md="3" :lg="3" :xl="3">
        <p style="text-align: center;">
          {{ c.edad }}
        </p>
      </el-col>
      <el-col :xs="3" :sm="3" :md="3" :lg="3" :xl="3">
        <p style="text-align: center;">
          {{ c.tipo_paciente }}
        </p>
      </el-col>
      <el-col :xs="3" :sm="3" :md="3" :lg="3" :xl="3">
        <p style="text-align: center;">
          {{ c.riesgo }}
        </p>
      </el-col>
      <el-col :xs="3" :sm="3" :md="3" :lg="3" :xl="3">
        <p style="text-align: center;">
          {{ c.prioridad }}
        </p>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  export default {
    props: {
      pacientes: {
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
        tipos_pacientes: [
          { nombre: 'Nino' },
          { nombre: 'Joven' },
          { nombre: 'Anciano' }
        ],
        filters: {},
        list: []
      }
    },
    computed: {
      pacientesFiltered() {
        var list = this.pacientes
        if (this.filters.numero_historia_clinica) {
          list = list.filter((c) => {
            return c['numero_historia_clinica'].toString().match(this.filters.numero_historia_clinica)
          })
        }
        if (this.filters.nombre) {
          list = list.filter((c) => {
            return c['nombre'].toLowerCase().match(this.filters.nombre.toLowerCase())
          })
        }
        if (this.filters.edad) {
          list = list.filter((c) => {
            return c['edad'].toString().match(this.filters.edad)
          })
        }
        if (this.filters.tipo_paciente) {
          list = list.filter((c) => {
            return c['tipo_paciente'].toString().match(this.filters.tipo_paciente)
          })
        }
        return list
      },
      pacientesList() {
        return this.pacientesFiltered
      }
    },
    created() {
      // this.getDataInicio()
    },
    methods: {
      /* getDataInicio() {
        this.axios
          .get(`http://localhost:30019/api/pacientes`, { tabs: 'pacientes'} )
            .then((response) => {
              this.pacientes = response.data;
              console.log(response.data);
          });
      }, */
      sortList(field, order) {
        this.pacientes.sort(function(a, b) {
          return order === 'up' ? a[field] > b[field] ? 1 : -1 : b[field] > a[field] ? 1 : -1
        })
        // console.log(this.pacientes)
      }
    }
  }
</script>
<style scoped>
.el-divider {
  margin-top: 12px;
  margin-bottom: 15px;
}
</style>
