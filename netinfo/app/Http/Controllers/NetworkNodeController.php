<?php

namespace App\Http\Controllers;

use App\Models\NetworkNode;
use App\Models\Customer;
use Illuminate\Http\Request;

class NetworkNodeController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->string('status')->toString();

        $nodes = NetworkNode::query()
            ->when(in_array($status, ['active', 'maintenance', 'down']), fn ($qq) => $qq->where('status', $status))
            ->withCount(['customers as active_customers' => fn ($q) => $q->where('status', 'active')])
            ->orderBy('name')
            ->get();

        $base = NetworkNode::query();

        return view('nodes.index', [
            'nodes' => $nodes,
            'filters' => ['status' => $status],
            'stats' => [
                'total' => (clone $base)->count(),
                'active' => (clone $base)->where('status', 'active')->count(),
                'maintenance' => (clone $base)->where('status', 'maintenance')->count(),
                'down' => (clone $base)->where('status', 'down')->count(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:network_nodes,name'],
            'location_area' => ['required', 'string', 'max:255'],
            'ip_address' => ['nullable', 'string', 'max:45'],
            'status' => ['required', 'in:active,maintenance,down'],
        ]);

        NetworkNode::create($data);

        return redirect()->route($this->indexRoute($request))->with('success', "Node {$data['name']} berhasil ditambahkan.");
    }

    public function update(Request $request, NetworkNode $node)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:network_nodes,name,' . $node->id],
            'location_area' => ['required', 'string', 'max:255'],
            'ip_address' => ['nullable', 'string', 'max:45'],
            'status' => ['required', 'in:active,maintenance,down'],
        ]);

        $from = $node->status;
        $node->update($data);
        $to = $data['status'];

        $message = "Perubahan node {$node->name} tersimpan ke database.";

        if ($to !== $from) {
            if ($to !== 'active') {
                $affected = Customer::where('node_id', $node->id)
                    ->where('status', 'active')
                    ->update(['status' => 'isolated', 'isolated_by_node_id' => $node->id]);

                if ($affected > 0) {
                    $label = $to === 'down' ? 'Down' : 'Maintenance';
                    $message = "Node {$node->name} menjadi {$label}. {$affected} pelanggan terhubung otomatis diisolir.";
                }
            } else {
                $restored = Customer::where('isolated_by_node_id', $node->id)
                    ->where('status', 'isolated')
                    ->update(['status' => 'active', 'isolated_by_node_id' => null]);

                if ($restored > 0) {
                    $message = "Node {$node->name} kembali Active. {$restored} pelanggan ter-isolir otomatis berhasil dipulihkan.";
                }
            }
        }

        return redirect()->route($this->indexRoute($request))->with('success', $message);
    }

    public function destroy(Request $request, NetworkNode $node)
    {
        abort_unless($request->user()->isAdmin(), 403);

        if ($node->customers()->exists()) {
            return redirect()->route($this->indexRoute($request))->with('error', "Node {$node->name} masih memiliki pelanggan terhubung sehingga tidak dapat dihapus.");
        }

        $name = $node->name;
        $node->delete();

        return redirect()->route($this->indexRoute($request))->with('success', "Node {$name} berhasil dihapus.");
    }

    private function indexRoute(Request $request): string
    {
        return $request->user()->isAdmin() ? 'admin.network-nodes.index' : 'technician.network-nodes.index';
    }
}
