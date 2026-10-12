import {
    ACESFilmicToneMapping, AmbientLight, DirectionalLight, ExtrudeGeometry, Group,
    Mesh, MeshPhysicalMaterial, PerspectiveCamera, PMREMGenerator,
    PCFSoftShadowMap, PointLight, Scene, Shape, SRGBColorSpace, WebGLRenderer,
} from 'three';
import { RoomEnvironment } from 'three/addons/environments/RoomEnvironment.js';

// Front-face contours traced from the overlapping ribbons in sch-icon.png.
// Coordinates share the source artwork's 1254px square to preserve its proportions.
const ribbons = [
    { tone: 'right', z: -0.04, points: [[946, 475], [1094, 379], [1123, 399], [1123, 1014], [946, 1138]] },
    { tone: 'roof', z: 0.02, points: [[382, 249], [650, 90], [893, 226], [914, 488], [646, 335], [480, 249]] },
    { tone: 'lower', z: 0.08, points: [[169, 945], [344, 831], [809, 1091], [816, 1126], [609, 1218], [169, 987]] },
    { tone: 'middle', z: 0.13, points: [[163, 419], [196, 462], [792, 799], [818, 837], [818, 1095], [790, 1047], [179, 690], [163, 658]] },
    { tone: 'cross', z: 0.18, points: [[680, 453], [945, 605], [1123, 705], [1123, 829], [946, 831], [681, 674]] },
    { tone: 'fold', z: 0.23, points: [[217, 310], [382, 249], [480, 249], [646, 335], [354, 502], [225, 427], [205, 391]] },
];

const x = value => (value - 643) / 300;
const y = value => (654 - value) / 300;

function makeShape(points) {
    const shape = new Shape();
    shape.moveTo(x(points[0][0]), y(points[0][1]));
    for (let index = 1; index < points.length; index++) shape.lineTo(x(points[index][0]), y(points[index][1]));
    shape.closePath();
    return shape;
}

function smoothBevelNormals(geometry) {
    // ExtrudeGeometry has separate, unindexed cap and side groups. Blend only
    // nearby side normals so the broad caps and sharp ribbon corners stay planar.
    const positions = geometry.getAttribute('position');
    const normals = geometry.getAttribute('normal');
    const sideGroup = geometry.groups.find(group => group.materialIndex === 1);
    if (!sideGroup) return;

    const coincident = new Map();
    for (let index = sideGroup.start; index < sideGroup.start + sideGroup.count; index++) {
        const key = `${positions.getX(index).toFixed(5)},${positions.getY(index).toFixed(5)},${positions.getZ(index).toFixed(5)}`;
        if (!coincident.has(key)) coincident.set(key, []);
        coincident.get(key).push(index);
    }

    const original = normals.array.slice();
    for (const indices of coincident.values()) {
        for (const index of indices) {
            const offset = index * 3;
            const nx = original[offset];
            const ny = original[offset + 1];
            const nz = original[offset + 2];
            let sx = 0, sy = 0, sz = 0;
            for (const neighbor of indices) {
                const candidate = neighbor * 3;
                const dot = nx * original[candidate] + ny * original[candidate + 1] + nz * original[candidate + 2];
                if (dot < 0.85) continue;
                sx += original[candidate];
                sy += original[candidate + 1];
                sz += original[candidate + 2];
            }
            const length = Math.hypot(sx, sy, sz) || 1;
            normals.setXYZ(index, sx / length, sy / length, sz / length);
        }
    }
    normals.needsUpdate = true;
}

export function createHeroScene(mount, { compact = false } = {}) {
    let renderer;
    let scene;
    try {
        renderer = new WebGLRenderer({ alpha: true, antialias: !compact, powerPreference: compact ? 'low-power' : 'high-performance' });
        renderer.setClearColor(0x000000, 0);
        renderer.outputColorSpace = SRGBColorSpace;
        renderer.toneMapping = ACESFilmicToneMapping;
        renderer.toneMappingExposure = 0.92;
        renderer.shadowMap.enabled = true;
        renderer.shadowMap.type = PCFSoftShadowMap;
        renderer.domElement.setAttribute('aria-hidden', 'true');
        renderer.domElement.setAttribute('tabindex', '-1');
        renderer.domElement.style.pointerEvents = 'none';
        mount.appendChild(renderer.domElement);

        scene = new Scene();
        const room = new RoomEnvironment();
        const pmrem = new PMREMGenerator(renderer);
        try {
            scene.userData.environmentTarget = pmrem.fromScene(room, 0, 0.1, 100, { size: compact ? 96 : 256 });
            scene.environment = scene.userData.environmentTarget.texture;
            scene.environmentIntensity = 0.68;
        } finally {
            room.dispose();
            pmrem.dispose();
        }
        const camera = new PerspectiveCamera(35, 1, 0.1, 30);
        camera.position.set(0, 0, 7.7);
        camera.lookAt(0, 0, 0);
        const structure = new Group();
        structure.rotation.set(-0.10, -0.17, 0);
        structure.scale.setScalar(0.78);
        structure.userData.baseY = compact ? 0.025 : 0.07;
        structure.position.set(compact ? 0.04 : 0.14, structure.userData.baseY, 0);
        scene.add(structure);

        const blueFinish = {
            metalness: 0.5, roughness: 0.3, clearcoat: 0.32, clearcoatRoughness: 0.28,
            envMapIntensity: 1.08, specularIntensity: 0.82, specularColor: '#bfe6ff',
            emissive: '#0057c7', emissiveIntensity: 0.018,
        };
        const navyFinish = {
            metalness: 0.46, roughness: 0.4, clearcoat: 0.22, clearcoatRoughness: 0.36,
            envMapIntensity: 0.76, specularIntensity: 0.64, specularColor: '#72b9ef',
            emissive: '#061e45', emissiveIntensity: 0.035,
        };
        const side = new MeshPhysicalMaterial({
            color: '#03142f', ...navyFinish, roughness: 0.44, envMapIntensity: 0.64,
        });
        const faces = {
            right: new MeshPhysicalMaterial({ color: '#079be8', ...blueFinish }),
            roof: new MeshPhysicalMaterial({ color: '#0786dc', ...blueFinish }),
            lower: new MeshPhysicalMaterial({ color: '#052f6d', ...navyFinish }),
            middle: new MeshPhysicalMaterial({ color: '#04285c', ...navyFinish }),
            cross: new MeshPhysicalMaterial({ color: '#056bc2', ...blueFinish }),
            fold: new MeshPhysicalMaterial({ color: '#063674', ...navyFinish }),
        };
        ribbons.forEach(({ tone, z, points }) => {
            const geometry = new ExtrudeGeometry(makeShape(points), {
                depth: 0.19, steps: 1, bevelEnabled: true, bevelThickness: 0.024,
                bevelSize: 0.018, bevelSegments: compact ? 3 : 6, curveSegments: 1,
            });
            smoothBevelNormals(geometry);
            const piece = new Mesh(geometry, [faces[tone], side]);
            piece.position.z = z;
            piece.castShadow = true;
            piece.receiveShadow = true;
            structure.add(piece);
        });

        scene.add(new AmbientLight('#739dcc', 0.2));
        const key = new DirectionalLight('#bfeaff', 2.65);
        key.position.set(4.2, 5.4, 6);
        key.castShadow = true;
        key.shadow.mapSize.set(compact ? 512 : 2048, compact ? 512 : 2048);
        key.shadow.camera.near = 1;
        key.shadow.camera.far = 16;
        key.shadow.camera.left = -3;
        key.shadow.camera.right = 3;
        key.shadow.camera.top = 3;
        key.shadow.camera.bottom = -3;
        key.shadow.bias = -0.0007;
        key.shadow.normalBias = 0.025;
        key.shadow.radius = compact ? 1.5 : 3;
        scene.add(key);

        const fill = new PointLight('#245da5', 8.5, 11);
        fill.position.set(-3.4, -1.8, 4.2);
        scene.add(fill);

        if (!compact) {
            const rim = new PointLight('#1fb6ff', 10, 10);
            rim.position.set(3.8, 3.2, 2.6);
            scene.add(rim);

            const edge = new DirectionalLight('#5aa7ff', 0.65);
            edge.position.set(-2.5, 1.2, 4.5);
            scene.add(edge);
        }
        return { renderer, scene, camera, structure };
    } catch (error) {
        if (scene) disposeHeroScene({ scene, renderer });
        else if (renderer) {
            renderer.dispose();
            renderer.domElement.remove();
        }
        throw error;
    }
}

export function disposeHeroScene({ scene, renderer }, releaseContext = true) {
    if (scene) {
        const geometries = new Set();
        const materials = new Set();
        scene.traverse(object => {
            if (object.geometry) geometries.add(object.geometry);
            if (object.material) {
                for (const material of Array.isArray(object.material) ? object.material : [object.material]) materials.add(material);
            }
        });
        geometries.forEach(geometry => geometry.dispose());
        materials.forEach(material => material.dispose());
        scene.userData.environmentTarget?.dispose();
        scene.environment = null;
        scene.clear();
    }
    if (renderer) {
        renderer.dispose();
        if (releaseContext) renderer.forceContextLoss();
        renderer.domElement.remove();
    }
}
