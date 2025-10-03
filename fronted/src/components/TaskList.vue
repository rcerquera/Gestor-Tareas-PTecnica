<template>
    <div class="container py-5">
        <h1 class="display-4 fw-bold text-center mb-5 text-primary">Gestión de Tareas</h1>

        <div class="row justify-content-center mb-4">
            <div class="col-md-6">
                <div class="card shadow-lg rounded-4 border-0">
                    <div class="card-body">
                        <h2 class="h6 mb-3 text-secondary">Agregar nueva palabra clave</h2>
                        <div class="input-group">
                            <input v-model="newKeyword" type="text" placeholder="Nombre de la palabra clave"
                                class="form-control form-control-lg" :class="{ 'is-invalid': keywordError }" />
                            <button @click="createKeyword" :disabled="loadingKeyword" class="btn btn-success btn-lg">
                                <i class="bi bi-plus-lg"></i> {{ loadingKeyword ? "Guardando..." : "Agregar" }}
                            </button>
                        </div>
                        <div v-if="keywordError" class="invalid-feedback d-block fw-semibold">
                            {{ keywordError }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mb-4">
            <div class="col-md-8">
                <div class="card shadow-lg rounded-4 border-0">
                    <div class="card-body">
                        <h2 class="h5 mb-3 text-secondary">Agregar nueva tarea</h2>
                        <div class="form-floating mb-3">
                            <input v-model="newTask" type="text" class="form-control" id="floatingTask"
                                placeholder="Nombre de la tarea" />
                            <label for="floatingTask">Nombre de la tarea</label>
                        </div>
                        <label class="form-label mb-2">Palabras clave</label>
                        <select v-model="selectedKeywords" multiple class="form-select mb-3">
                            <option v-for="k in keywords" :key="k.id" :value="k.id">{{ k.name }}</option>
                        </select>
                        <button @click="createTask" :disabled="loading" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-check2-circle"></i> {{ loading ? "Guardando..." : "Agregar tarea" }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4 shadow-sm rounded-4 border-0">
            <div class="card-body d-flex flex-wrap gap-3 align-items-center">
                <select v-model="filterState" class="form-select w-auto">
                    <option value="">Todos los estados</option>
                    <option value="true">Completadas</option>
                    <option value="false">Pendientes</option>
                </select>
                <select v-model="filterKeyword" class="form-select w-auto">
                    <option value="">Todas las palabras clave</option>
                    <option v-for="k in keywords" :key="k.id" :value="k.id">{{ k.name }}</option>
                </select>
            </div>
        </div>

        <!-- Loader -->
        <Loader :loading="loadingData" />

        <draggable v-if="!loadingData && filteredTasks.length" v-model="filteredTasks" item-key="id" class="mb-4"
            handle=".handle">
            <template #item="{ element: task }">
                <div class="card mb-3 shadow-lg rounded-4 border-0 hover-shadow">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="mb-1">
                                <span class="fw-bold fs-5 text-dark">{{ task.title }}</span>
                                <span v-for="k in task.keywords" :key="k.id" class="badge bg-info text-dark ms-2">
                                    {{ k.name }}
                                </span>
                            </div>
                            <span :class="task.is_done ? 'badge bg-success' : 'badge bg-warning text-dark'"
                                class="me-2">
                                <i :class="task.is_done ? 'bi bi-check-circle-fill' : 'bi bi-hourglass-split'"></i>
                                {{ task.is_done ? 'Completada' : 'Pendiente' }}
                            </span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="handle text-muted fs-4" style="cursor:grab;">
                                <i class="bi bi-grip-vertical"></i>
                            </span>
                            <button @click="toggleTask(task.id)" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-arrow-repeat"></i> Cambiar estado
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </draggable>

        <div v-if="!loadingData && !filteredTasks.length" class="text-center text-muted mt-4">
            <i class="bi bi-clipboard-x fs-1"></i>
            <div>No hay tareas para mostrar.</div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import draggable from "vuedraggable";
import Loader from "./Loader.vue";

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL;
const api = axios.create({ baseURL: API_BASE_URL });

const tasks = ref([]);
const keywords = ref([]);
const newTask = ref("");
const selectedKeywords = ref([]);
const loading = ref(false);
const loadingData = ref(true);
const filterState = ref("");
const filterKeyword = ref("");
const newKeyword = ref("");
const loadingKeyword = ref(false);
const keywordError = ref("");

const filteredTasks = computed(() => {
    return tasks.value.filter(t => {
        if (filterState.value) {
            const filterNum = filterState.value === "true" ? 1 : 0;
            if (t.is_done !== filterNum) return false;
        }
        if (filterKeyword.value) {
            const kwId = Number(filterKeyword.value);
            if (!t.keywords.some(k => k.id === kwId)) return false;
        }
        return true;
    });
});

const fetchData = async () => {
    loadingData.value = true;
    let didTimeout = false;

    const timer = setTimeout(() => {
        didTimeout = true;
        loadingData.value = false;
        alert("La carga de datos tardó demasiado. Intenta nuevamente.");
    }, 8000);

    try {
        const [tasksRes, keywordsRes] = await Promise.all([
            api.get("/api/tasks"),
            api.get("/api/keywords")
        ]);
        if (!didTimeout) {
            tasks.value = tasksRes.data;
            keywords.value = keywordsRes.data;
        }
    } catch (err) {
        console.error(err);
        if (!didTimeout) alert("Error al cargar datos.");
    } finally {
        clearTimeout(timer);
        loadingData.value = false;
    }
};

const createTask = async () => {
    if (!newTask.value.trim()) return;
    loading.value = true;
    try {
        await api.post("/api/tasks", { title: newTask.value, keywords: selectedKeywords.value });
        newTask.value = "";
        selectedKeywords.value = [];
        fetchData();
    } catch (err) {
        console.error(err);
        alert("Error al crear tarea.");
    } finally {
        loading.value = false;
    }
};

const createKeyword = async () => {
    keywordError.value = "";
    if (!newKeyword.value.trim()) {
        keywordError.value = "El nombre es obligatorio.";
        return;
    }
    loadingKeyword.value = true;
    try {
        await api.post("/api/keywords", { name: newKeyword.value });
        newKeyword.value = "";
        fetchData();
    } catch (err) {
        console.error(err);
        keywordError.value = "Error al crear palabra clave.";
    } finally {
        loadingKeyword.value = false;
    }
};

const toggleTask = async (id) => {
    try {
        await api.patch(`/api/tasks/${id}/toggle`);
        fetchData();
    } catch (err) {
        console.error(err);
        alert("Error al cambiar estado de la tarea.");
    }
};

onMounted(fetchData);
</script>

<style scoped>
.hover-shadow {
    transition: all 0.2s cubic-bezier(.4, 0, .2, 1);
}

.hover-shadow:hover {
    transform: translateY(-2px) scale(1.02);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.13);
}
</style>