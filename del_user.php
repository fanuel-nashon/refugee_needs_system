<?php
use App\Models\User;
$u = User::where('email', 'juma.trades@gmail.com')->first();
if ($u) { $u->delete(); echo "Deleted: {$u->email}\n"; }
else { echo "Not found.\n"; }
